<?php
/**
 * This file is part of the "archinspec" project.
 *
 * (c) Fabian Keller <hello@fabian-keller.de>
 *
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
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