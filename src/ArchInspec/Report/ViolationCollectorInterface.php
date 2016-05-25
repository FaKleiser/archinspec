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
 * Used to collect {@link PolicyViolation}s .
 */
interface ViolationCollectorInterface
{
    /**
     * Adds a policy violation with "major" severity to the report.
     *
     * An major policy violation should not be shipped to production.
     *
     * @param PolicyViolation $violation
     *
     * @return ViolationCollectorInterface Provides fluent interface
     */
    public function major(PolicyViolation $violation);

    /**
     * Adds a policy violation with "minor" severity to the report.
     *
     * A minor policy violation is discouraged, but is allowed to be shipped to production.
     *
     * @param PolicyViolation $violation
     *
     * @return ViolationCollectorInterface Provides fluent interface
     */
    public function minor(PolicyViolation $violation);

    /**
     * Add policy violations for {@link IEvaluationResult#UNDEFINED} results.
     *
     * @param PolicyViolation $violation
     *
     * @return ViolationCollectorInterface Provides fluent interface
     */
    public function undefined(PolicyViolation $violation);
}