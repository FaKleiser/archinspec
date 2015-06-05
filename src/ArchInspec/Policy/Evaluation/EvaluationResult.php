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

    /**
     * Constructs the evaluation result. Private constructor prevents invalid usage.
     *
     * @param int $result
     * @param string $message
     */
    private function __construct($result, $message = "")
    {
        $this->result = $result;
        $this->message = $message;
    }

    /**
     * Creates an evaluation result representing an allowed usage.
     *
     * @param string $message
     * @return IEvaluationResult
     */
    public static function allowed($message = "")
    {
        return new static(self::ALLOWED, $message);
    }

    /**
     * Creates an evaluation result representing a denied usage.
     *
     * @param string $message
     * @return IEvaluationResult
     */
    public static function denied($message = "")
    {
        return new static(self::DENIED, $message);
    }

    /**
     * Creates an evaluation result representing an undefined usage.
     *
     * @param string $message
     * @return IEvaluationResult
     */
    public static function undefined($message = "")
    {
        return new static(self::UNDEFINED, $message);
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
    public function equals(IEvaluationResult $other)
    {
        return $this->result === $other->getResult();
    }
}