<?php
/**
 * This file is part of the "archinspec" project.
 *
 * (c) Fabian Keller <hello@fabian-keller.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArchInspec\Command;

use ArchInspec\Application\AIConfig;
use ArchInspec\Application\ArchInspec;
use ArchInspec\Report\Writer\ConsoleWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * This command is the entry point for evaluating the architecture based on defined architecture definitions.
 */
class InspectCommand extends Command
{
    const EXIT_SUCCESS = 0, EXIT_VIOLATION = 1, EXIT_FAILED = 3;

    const ARGUMENT_CONFIG = 'config';
    const OPTION_REPORT_UNDEFINED = 'reportUndefined';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $defaultConfig = realpath(__DIR__ . '/../../../archinspec.yml.dist');
        $this->addArgument(self::ARGUMENT_CONFIG, InputArgument::OPTIONAL, CliMessage::ARGUMENT_CONFIG, $defaultConfig);
        $this->addOption(self::OPTION_REPORT_UNDEFINED, 'u', InputOption::VALUE_NONE,
            CliMessage::OPTION_REPORT_UNDEFINED);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configFile = $input->getArgument(self::ARGUMENT_CONFIG);
        $output->writeln($this->getDescription() . PHP_EOL);

        // read config
        $output->writeln(CliMessage::READ_CONFIG_FROM . $configFile);
        $config = AIConfig::fromYamlFile($configFile);
        if ($input->getOption(self::OPTION_REPORT_UNDEFINED)) {
            $config->setReportUndefined(true);
        }

        if (!$this->isValidArchitectureFile($config, $input, $output)) {
            return self::EXIT_FAILED;
        }
        $output->writeln(CliMessage::READ_ARCHITECTURE_FILE_FROM . $config->getArchitecture());
        $output->writeln(CliMessage::STARTING_ANALYSIS);
        $output->writeln("");

        $archInspec = new ArchInspec($config);
        if ($archInspec->analyze(new ConsoleWriter($output))) {
            return self::EXIT_SUCCESS;
        } else {
            return self::EXIT_VIOLATION;
        }
    }

    /**
     * Makes sure the architecture file is readable and helps the user if not.
     *
     * @param AIConfig $config
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return bool
     */
    private function isValidArchitectureFile(AIConfig $config, InputInterface $input, OutputInterface $output)
    {
        $arcFile = $config->getArchitecture();
        if (is_readable($arcFile)) {
            return true;
        }

        if (file_exists($arcFile)) {
            // permission error
            $output->writeln(sprintf(CliMessage::ARCHITECTURE_FILE_NOT_READABLE, $arcFile));

        } else {
            // no architecture file present
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion(sprintf(CliMessage::ARCHITECTURE_FILE_ASK_TO_CREATE, $arcFile), false);
            if ($helper->ask($input, $output, $question)) {
                touch($arcFile);
                $output->writeln(sprintf(CliMessage::ARCHITECTURE_FILE_CREATED_EMPTY, $arcFile));
            }
            $output->writeln(CliMessage::ARCHITECTURE_FILE_HOWTO);
        }

        return false;
    }


}