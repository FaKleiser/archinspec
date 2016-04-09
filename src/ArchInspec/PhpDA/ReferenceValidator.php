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

namespace ArchInspec\PhpDA;

use ArchInspec\Inspector\Inspector;
use ArchInspec\Policy\Evaluation\EvaluationResult;
use ArchInspec\Policy\Evaluation\IEvaluationResult;
use PhpDA\Reference\ValidatorInterface;
use PhpParser\Node\Name;

class ReferenceValidator implements ValidatorInterface
{
    /**
     * Note: the use of the singleton pattern here is highly discouraged. However, there is currently an issue in the
     * phpDependencyAnalysis library that does not allow to inject any dependencies (either through constructors or
     * setters) into the reference validator. The singelton currently allows to do that, but the dependencies should be
     * properly injected as soon as the issue in phpDA is resolved.
     *
     * @see https://github.com/mamuz/PhpDependencyAnalysis/issues/22
     *
     * @var  ReferenceValidator - holds the singleton instance of the validator.
     */
    private static $instance;

    /** @var Inspector */
    private $inspector;
    /** @var IEvaluationResult */
    private $lastResult;

    public function __construct()
    {
        if (!is_null(self::$instance)) {
            $this->inspector = self::$instance->inspector;
        }
        self::$instance = $this;
    }

    /**
     * @deprecated this method must be replaced in the near future with proper dependency injection techniques! See {@link ReferenceValidator#instance} for more information.
     * @return ReferenceValidator
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Set the inspector to be used for the analysis.
     *
     * @param Inspector $inspector
     */
    public function setInspector($inspector)
    {
        $this->inspector = $inspector;
    }

    /**
     * {@inheritdoc}
     */
    public function isValidBetween(Name $from, Name $to)
    {
        $this->lastResult = $this->inspector->isAllowed($from->toString(), $to->toString());
        return $this->lastResult->equals(EvaluationResult::allowed());
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages()
    {
        return [$this->lastResult->getMessage()];
    }
}