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
    /** @var Inspector */
    private $inspector;
    /** @var IEvaluationResult */
    private $lastResult;

    public function __construct()
    {
        $this->inspector = new Inspector();
        $this->inspector->load('architecture.yml');
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