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

use ArchInspec\Report\PolicyViolation;
use ArchInspec\Report\PolicyViolationReport;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Writes {@link PolicyViolationReport}s to the console.
 */
class ConsoleWriter implements ReportWriterInterface
{
    /** @var OutputInterface */
    private $out;


    public function __construct(OutputInterface $out)
    {
        $this->out = $out;
    }

    /**
     * {@inheritdoc}
     */
    public function write(PolicyViolationReport $report)
    {
        if (!$report->hasViolations()) {
            $this->out->writeln("<info>No policy violations found!</info>");
            return;
        }

        $this->out->writeln(sprintf("<error>%d policy violations found!</error>", count($report->getViolations())));
        foreach ($report->getViolations() as $from => $violations) {
            $this->out->writeln(sprintf("<comment>%s has %d violations:</comment>", $from, count($violations)));
            foreach ($violations as $violation) {
                /** @var PolicyViolation $violation */
                $this->out->writeln(sprintf("  uses <comment>%s</comment>: %s", $violation->getTo(), $violation->getCause()));
                if (!is_null($violation->getReason())) {
                    $this->out->writeln(sprintf("  > Reason: %s", $violation->getReason()));
                }
            }
            $this->out->writeln("");
        }
    }
}