<?php
/**
 * This file is part of the "archinspec" project.
 *
 * (c) Fabian Keller <hello@fabian-keller.de>
 *
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
 */

namespace ArchInspec\Application;

use ArchInspec\Inspector\Inspector;
use ArchInspec\PhpDA\ReferenceValidator;
use ArchInspec\Report\PolicyViolationReport;
use ArchInspec\Report\Writer\ReportWriterInterface;
use PhpDA\Command\Config;
use PhpDA\Command\Strategy\UsageFactory;
use PhpDA\Parser\Visitor\Required\DeclaredNamespaceCollector;
use PhpDA\Parser\Visitor\Required\MetaNamespaceCollector;
use PhpDA\Parser\Visitor\Required\UsedNamespaceCollector;
use PhpDA\Parser\Visitor\SuperglobalCollector;
use PhpDA\Parser\Visitor\TagCollector;
use PhpDA\Writer\Strategy\Html;

/**
 * Sets up and executes the architecture analysis.
 */
class ArchInspec
{
    /** @var AIConfig */
    private $config;

    public function __construct(AIConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Analyses the architecture.
     *
     * @return bool true if the architecture raised no violation, false if there were violations
     */
    public function analyze(ReportWriterInterface $writer)
    {
        $config = $this->createPhpDAConfig();

        $usageFactory = new UsageFactory();
        $usage = $usageFactory->create();
        $usage->setOptions(['config' => $config]);

        // collect violation report
        $report = new PolicyViolationReport();

        $inspector = new Inspector();
        $inspector->load($this->config->getArchitecture());
        ReferenceValidator::getInstance()->setInspector($inspector);
        ReferenceValidator::getInstance()->setViolationCollector($report);
        $result = $usage->execute();

        // write report
        $writer->write($report);
        return $result;
    }

    /**
     * @return Config
     */
    private function createPhpDAConfig()
    {
        return new Config([
            'mode' => Config::USAGE_MODE,
            'source' => $this->config->getSource(),
            'ignore' => $this->config->getPhpDa()['ignore'],
            'formatter' => Html::class,
            'target' => $this->config->getOutput() . '/analysis.html',
            'filePattern' => $this->config->getPhpDa()['filePattern'],
            'groupLength' => 2,
            'visitor' => [
                TagCollector::class,
                SuperglobalCollector::class,
            ],
            'visitorOptions' => [
                DeclaredNamespaceCollector::class => ['minDepth' => 2, 'sliceLength' => 20],
                MetaNamespaceCollector::class => ['minDepth' => 2, 'sliceLength' => 20],
                UsedNamespaceCollector::class => ['minDepth' => 2, 'sliceLength' => 20],
                TagCollector::class => ['minDepth' => 2, 'sliceLength' => 20],
            ],
            'referenceValidator' => ReferenceValidator::class,
        ]);
    }
}