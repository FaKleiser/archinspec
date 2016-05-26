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
 * Represents the evaluation result of defined policies for a specific architecture relation.
 */
interface IEvaluationResult
{
    /** Represents allowed policy evaluation */
    const ALLOWED = 1;
    /** Represents denied policy evaluation */
    const DENIED = 2;
    /** Represents undefined policy evaluation */
    const UNDEFINED = 3;

    /**
     * Returns the result type. The returned integer is a value of the class constants of {@link IEvaluationResult}.
     *
     * @return int code
     */
    public function getResult();

    /**
     * @return boolean true if the evaluation result is {@link IEvaluationResult#ALLOWED}.
     */
    public function isAllowed();

    /**
     * @return boolean true if the evaluation result is {@link IEvaluationResult#DENIED}.
     */
    public function isDenied();

    /**
     * @return boolean true if the evaluation result is {@link IEvaluationResult#UNDEFINED}.
     */
    public function isUndefined();

    /**
     * Returns a reasoning message for the policy evaluation result.
     *
     * @return string
     */
    public function getMessage();

    /**
     * Returns the policy that caused this result.
     *
     * @return PolicyInterface
     */
    public function causedBy();
}