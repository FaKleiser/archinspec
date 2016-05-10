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

namespace ArchInspec\Policy\Evaluation;

use ArchInspec\Policy\PolicyInterface;

/**
 * Represents the result of a {@link PolicyInterface} evaluation on a {link NodeInterface}.
 *
 * @package ArchInspec\Policy\Evaluation
 */
class EvaluationResult implements IEvaluationResult
{

    /** @var int the evaluation result */
    private $result;
    /** @var  string stores the result message */
    private $message;
    /** @var PolicyInterface policy that caused this result */
    private $causedBy;

    /**
     * Constructs the evaluation result. Private constructor prevents invalid usage.
     *
     * @param int $result
     * @param PolicyInterface $causedBy
     * @param string $message
     */
    private function __construct($result, PolicyInterface $causedBy = null, $message = "")
    {
        $this->result = $result;
        $this->message = $message;
        $this->causedBy = $causedBy;
    }

    /**
     * Creates an evaluation result representing an allowed usage.
     *
     * @param string $message
     * @param PolicyInterface $causedBy
     * @return IEvaluationResult
     */
    public static function allowed(PolicyInterface $causedBy, $message = "")
    {
        return new static(self::ALLOWED, $causedBy, $message);
    }

    /**
     * Creates an evaluation result representing a denied usage.
     *
     * @param string $message
     * @param PolicyInterface $causedBy
     * @return IEvaluationResult
     */
    public static function denied(PolicyInterface $causedBy, $message = "")
    {
        return new static(self::DENIED, $causedBy, $message);
    }

    /**
     * Creates an evaluation result representing an undefined usage.
     *
     * @param string $message
     * @return IEvaluationResult
     */
    public static function undefined($message = "")
    {
        return new static(self::UNDEFINED, null, $message);
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * {@inheritdoc}
     */
    public function isAllowed()
    {
        return $this->getResult() === IEvaluationResult::ALLOWED;
    }

    /**
     * {@inheritdoc}
     */
    public function isDenied()
    {
        return $this->getResult() === IEvaluationResult::DENIED;
    }

    /**
     * {@inheritdoc}
     */
    public function isUndefined()
    {
        return $this->getResult() === IEvaluationResult::UNDEFINED;
    }


    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Returns the policy that caused this result.
     *
     * @return PolicyInterface
     */
    public function causedBy()
    {
        return $this->causedBy;
    }
}