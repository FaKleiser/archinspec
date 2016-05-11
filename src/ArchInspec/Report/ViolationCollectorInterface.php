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
     * Adds a policy violation to the report.
     *
     * @param PolicyViolation $violation
     *
     * @return ViolationCollectorInterface Provides fluent interface
     */
    public function report(PolicyViolation $violation);
}