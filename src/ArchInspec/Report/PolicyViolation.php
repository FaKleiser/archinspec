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

namespace ArchInspec\Report;

use ArchInspec\Policy\Evaluation\IEvaluationResult;
use PhpParser\Node\Name;

class PolicyViolation
{
    /** @var Name */
    private $from;
    /** @var Name */
    private $to;
    /** @var IEvaluationResult */
    private $cause;

    /**
     * PolicyViolation constructor.
     * @param Name $from
     * @param Name $to
     * @param IEvaluationResult $cause
     */
    public function __construct(Name $from, Name $to, IEvaluationResult $cause)
    {
        $this->from = $from;
        $this->to = $to;
        $this->cause = $cause;
    }

    /**
     * @return Name
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return Name
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Returns a string describing why the policy was violated
     *
     * @return string
     */
    public function getCause()
    {
        return $this->cause->getMessage();
    }

    /**
     * Returns a string describing why the policy exists at all.
     *
     * A reasoning may not exist and in that case the method will return null.
     *
     * @return null|string
     */
    public function getReason()
    {
        return $this->cause->causedBy()->getReason();
    }
}