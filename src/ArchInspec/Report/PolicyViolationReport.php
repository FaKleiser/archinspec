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


class PolicyViolationReport implements ViolationCollectorInterface
{
    /** @var PolicyViolation[] */
    private $violations = [];

    /**
     * {@inheritdoc}
     */
    public function report(PolicyViolation $violation)
    {
        $affects = $violation->getFrom()->toString();
        if (!isset($this->violations[$affects])) {
            $this->violations[$affects] = [];
        }
        $this->violations[$affects][] = $violation;
    }

    /**
     * Determines whether there are violations in the report.
     *
     * @return boolean
     */
    public function hasViolations()
    {
        return count($this->violations) > 0;
    }

    /**
     * Returns all violations of the report, group by the "from" part.
     *
     * @return PolicyViolation[][]
     */
    public function getViolations()
    {
        return $this->violations;
    }
}