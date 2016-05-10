<?php
/**
 * Created by PhpStorm.
 * User: Fabian
 * Date: 05.06.2015
 * Time: 19:13
 */

namespace ArchInspec\Policy\Evaluation;

use ArchInspec\Policy\PolicyInterface;

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
     * @return true if the evaluation result is {@link IEvaluationResult#ALLOWED}.
     */
    public function isAllowed();

    /**
     * @return true if the evaluation result is {@link IEvaluationResult#DENIED}.
     */
    public function isDenied();

    /**
     * @return true if the evaluation result is {@link IEvaluationResult#UNDEFINED}.
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