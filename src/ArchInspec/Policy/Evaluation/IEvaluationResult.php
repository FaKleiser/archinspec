<?php
/**
 * Created by PhpStorm.
 * User: Fabian
 * Date: 05.06.2015
 * Time: 19:13
 */

namespace ArchInspec\Policy\Evaluation;

interface IEvaluationResult
{
    /** Represents allowed policy evaluation */
    const ALLOWED = 1;
    /** Represents denied policy evaluation */
    const DENIED = 2;
    /** Represents undefined policy evaluation */
    const UNDEFINED = 3;

    /**
     * Returns true if the type of the evaluation results are equal.
     *
     * E.g. two evaluation results are equal if the are both of type "ALLOWED", for example.
     *
     * @param IEvaluationResult $other
     */
    public function equals(IEvaluationResult $other);

    /**
     * Returns the result type. The returned integer is a value of the class constants of {@link IEvaluationResult}.
     *
     * @return int
     */
    public function getResult();

    /**
     * Returns a reasoning message for the policy evaluation result.
     *
     * @return string
     */
    public function getMessage();
}