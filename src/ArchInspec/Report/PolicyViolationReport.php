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

/**
 * Stores a report on all found policy violations.
 */
class PolicyViolationReport implements ViolationCollectorInterface
{
    /** @var boolean whether or not to ignore undefined violations */
    private $reportUndefined;

    /** @var PolicyViolation[][] */
    private $major = [];
    /** @var PolicyViolation[][] */
    private $minor = [];
    /** @var PolicyViolation[][] */
    private $undefined = [];


    public function __construct($reportUndefined = false)
    {
        $this->reportUndefined = $reportUndefined;
    }

    /**
     * {@inheritdoc}
     */
    public function major(PolicyViolation $violation)
    {
        $this->storeViolation($this->major, $violation);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function minor(PolicyViolation $violation)
    {
        $this->storeViolation($this->minor, $violation);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function undefined(PolicyViolation $violation)
    {
        if ($this->reportUndefined) {
            $this->storeViolation($this->undefined, $violation);
        }
        return $this;
    }

    /**
     * Stores the violation as a map with the "from" part of the violation being used as key.
     *
     * @param PolicyViolation[][] $storage violation map
     * @param PolicyViolation $violation
     */
    private function storeViolation(array &$storage, PolicyViolation $violation)
    {
        $affects = $violation->getFrom()->toString();
        if (!isset($storage[$affects])) {
            $storage[$affects] = [];
        }
        $storage[$affects][] = $violation;
    }

    /**
     * Determines whether the report has violations of any severity.
     *
     * @return bool true if the report has violations.
     */
    public function hasViolations()
    {
        return $this->hasMajorViolations() || $this->hasMinorViolations() || $this->hasUndefinedViolations();
    }

    /**
     * Determines whether there are major violations in the report.
     *
     * @return boolean
     */
    public function hasMajorViolations()
    {
        return count($this->major) > 0;
    }

    /**
     * Determines whether there are minor violations in the report.
     *
     * @return boolean
     */
    public function hasMinorViolations()
    {
        return count($this->minor) > 0;
    }

    /**
     * Determines whether there are violations due to undefined architectural relationships in the report.
     *
     * @return boolean
     */
    public function hasUndefinedViolations()
    {
        return count($this->undefined) > 0;
    }

    /**
     * Returns all major violations of the report, group by the "from" part of the violation.
     *
     * @return PolicyViolation[][]
     */
    public function getMajorViolations()
    {
        return $this->major;
    }

    /**
     * Returns all minor violations of the report, group by the "from" part of the violation.
     *
     * @return PolicyViolation[][]
     */
    public function getMinorViolations()
    {
        return $this->minor;
    }

    /**
     * Returns all undefined violations of the report, group by the "from" part of the violation.
     *
     * @return PolicyViolation[][]
     */
    public function getUndefinedViolations()
    {
        return $this->undefined;
    }
}