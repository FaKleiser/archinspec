<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Fabian Keller
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace ArchInspec\Report\Writer;

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
        foreach ($report->getViolations() as $violation) {
            $this->out->writeln(sprintf("<info>%s uses %s</info>", $violation->getFrom(), $violation->getTo()));
            $this->out->writeln($violation->getCause()->getMessage());
        }
    }
}