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

/**
 * This command is the entry point for evaluating the architecture based on defined architecture definitions.
 */
class InspectCommand extends Command
{
    const EXIT_SUCCESS = 0, EXIT_VIOLATION = 1;

    const ARGUMENT_CONFIG = 'config';
    const OPTION_REPORT_UNDEFINED = 'reportUndefined';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $defaultConfig = __DIR__ . '/../../../archinspec.yml.dist';
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
        $output->writeln(CliMessage::READ_CONFIG_FROM . $configFile . PHP_EOL);

        $config = AIConfig::fromYamlFile($configFile);
        if ($input->getOption(self::OPTION_REPORT_UNDEFINED)) {
            $config->setReportUndefined(true);
        }

        $archInspec = new ArchInspec($config);
        if ($archInspec->analyze(new ConsoleWriter($output))) {
            return self::EXIT_SUCCESS;
        } else {
            return self::EXIT_VIOLATION;
        }
    }


}