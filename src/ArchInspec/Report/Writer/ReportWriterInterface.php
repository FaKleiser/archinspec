<?php
/**
 * This file is part of the "archinspec" project.
 *
 * (c) Fabian Keller <hello@fabian-keller.de>
 *
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
 */

namespace ArchInspec\Report\Writer;

use ArchInspec\Report\PolicyViolationReport;

/**
 * Used to write {@link PolicyViolationReport}s to a persistent format.
 */
interface ReportWriterInterface
{
    /**
     * Writes the violation report.
     *
     * @param PolicyViolationReport $report
     *
     * @return void
     */
    public function write(PolicyViolationReport $report);
}