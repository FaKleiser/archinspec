<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Fabian Keller
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace ArchInspec\Command;

use ArchInspec\PhpDA\ReferenceValidator;
use PhpDA\Command\Config;
use PhpDA\Command\MessageInterface;
use PhpDA\Command\Strategy\UsageFactory;
use PhpDA\Parser\Visitor\Required\DeclaredNamespaceCollector;
use PhpDA\Parser\Visitor\Required\MetaNamespaceCollector;
use PhpDA\Parser\Visitor\Required\UsedNamespaceCollector;
use PhpDA\Parser\Visitor\SuperglobalCollector;
use PhpDA\Parser\Visitor\TagCollector;
use PhpDA\Writer\Strategy\Html;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;

class InspectCommand extends Command
{
    const EXIT_SUCCESS = 0, EXIT_VIOLATION = 1;

    /** @var string */
    private $configFilePath;

    /** @var Parser */
    private $configParser;


    /**
     * @param Parser $parser
     */
    public function setConfigParser(Parser $parser)
    {
        $this->configParser = $parser;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $defaultConfig = __DIR__ . '/../../archinspec.yml.dist';

        $this->addArgument('config', InputArgument::OPTIONAL, MessageInterface::ARGUMENT_CONFIG, $defaultConfig);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $this->createPhpDAConfig($input);

        $output->writeln($this->getDescription() . PHP_EOL);
        $output->writeln(MessageInterface::READ_CONFIG_FROM . $this->configFilePath . PHP_EOL);

        $usageFactory = new UsageFactory();
        $usage = $usageFactory->create();
        $usage->setOptions(['config' => $config]);
        if ($usage->execute()) {
            return self::EXIT_SUCCESS;
        } else {
            return self::EXIT_VIOLATION;
        }
    }

    /**
     * @param InputInterface $input
     * @throws \InvalidArgumentException
     * @return Config
     */
    private function createPhpDAConfig(InputInterface $input)
    {
        $this->configFilePath = realpath($input->getArgument('config'));
        $config = $this->configParser->parse(file_get_contents($this->configFilePath));

        if (!is_array($config)) {
            throw new \InvalidArgumentException('Configuration is invalid');
        }

        return new Config([
            'mode' => Config::USAGE_MODE,
            'source' => $config['source'],
            'ignore' => $config['ignore'],
            'formatter' => Html::class,
            'target' => $config['target'],
            'filePattern' => $config['filePattern'],
            'groupLength' => 2,
            'visitor' => [
                TagCollector::class,
                SuperglobalCollector::class,
            ],
            'visitorOptions' => [
                DeclaredNamespaceCollector::class => ['minDepth' => 3, 'sliceLength' => 3],
                MetaNamespaceCollector::class => ['minDepth' => 3, 'sliceLength' => 3],
                UsedNamespaceCollector::class => ['minDepth' => 3, 'sliceLength' => 3],
                TagCollector::class => ['minDepth' => 3, 'sliceLength' => 3],
            ],
            'referenceValidator' => ReferenceValidator::class,
        ]);
    }
}