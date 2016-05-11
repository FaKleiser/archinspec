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
use PhpDA\Command\MessageInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InspectCommand extends Command
{
    const EXIT_SUCCESS = 0, EXIT_VIOLATION = 1;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $defaultConfig = __DIR__ . '/../../../archinspec.yml.dist';
        $this->addArgument('config', InputArgument::OPTIONAL, CliMessage::ARGUMENT_CONFIG, $defaultConfig);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configFile = $input->getArgument('config');

        $output->writeln($this->getDescription() . PHP_EOL);
        $output->writeln(CliMessage::READ_CONFIG_FROM . $configFile . PHP_EOL);

        $config = AIConfig::fromYamlFile($configFile);
        $archInspec = new ArchInspec($config);
        if ($archInspec->analyze(new ConsoleWriter($output))) {
            return self::EXIT_SUCCESS;
        } else {
            return self::EXIT_VIOLATION;
        }
    }


}