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