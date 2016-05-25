<?php
/**
 * This file is part of the "archinspec" project.
 *
 * (c) Fabian Keller <hello@fabian-keller.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
    private $evaluation;

    /**
     * PolicyViolation constructor.
     *
     * @param Name $from
     * @param Name $to
     * @param IEvaluationResult $evaluation
     */
    public function __construct(Name $from, Name $to, IEvaluationResult $evaluation)
    {
        $this->from = $from;
        $this->to = $to;
        $this->evaluation = $evaluation;
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
        return $this->evaluation->getMessage();
    }

    /**
     * Checks whether there is a reason describing why the policy exists at all.
     *
     * @return boolean true if there is a reason
     */
    public function hasRationale()
    {
        return !is_null($this->evaluation->causedBy()) && !empty($this->evaluation->causedBy()->getRationale());
    }

    /**
     * Returns a string describing why the policy exists at all.
     *
     * A reasoning may not exist and in that case the method will return null.
     *
     * @return null|string
     */
    public function getRationale()
    {
        return $this->evaluation->causedBy()->getRationale();
    }
}