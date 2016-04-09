<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Fabian Keller
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

namespace ArchInspec\Application;

use ArchInspec\Inspector\Inspector;
use ArchInspec\PhpDA\ReferenceValidator;
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
    public function analyze()
    {
        $config = $this->createPhpDAConfig();

        $usageFactory = new UsageFactory();
        $usage = $usageFactory->create();
        $usage->setOptions(['config' => $config]);

        $inspector = new Inspector();
        $inspector->load($this->config->getArchitecture());
        ReferenceValidator::getInstance()->setInspector($inspector);
        return $usage->execute();
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
                DeclaredNamespaceCollector::class => ['minDepth' => 3, 'sliceLength' => 3],
                MetaNamespaceCollector::class => ['minDepth' => 3, 'sliceLength' => 3],
                UsedNamespaceCollector::class => ['minDepth' => 3, 'sliceLength' => 3],
                TagCollector::class => ['minDepth' => 3, 'sliceLength' => 3],
            ],
            'referenceValidator' => ReferenceValidator::class,
        ]);
    }
}