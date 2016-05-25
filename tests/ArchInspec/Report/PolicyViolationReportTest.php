<?php
/**
 * This file is part of the "archinspec" project.
 *
 * (c) Fabian Keller <hello@fabian-keller.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArchInspec\Inspector;

use ArchInspec\Report\PolicyViolation;
use ArchInspec\Report\PolicyViolationReport;
use PhpParser\Node\Name;

class PolicyViolationReportTest extends \PHPUnit_Framework_TestCase
{
    /** @var PolicyViolationReport */
    private $report;

    protected function setUp()
    {
        $this->report = new PolicyViolationReport(true);
    }

    /**
     * Mock a violation.
     *
     * @param string $from
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function violation($from, $to = "default")
    {
        $violation = $this->getMockBuilder(PolicyViolation::class)->disableOriginalConstructor()->getMock();
        $violation->expects($this->any())->method('getFrom')->willReturn(new Name($from));
        return $violation;
    }


    // // TEST MAJOR VIOLATIONS // //

    public function testHasMajorViolations_withNoViolations_isFalse()
    {
        $this->assertFalse($this->report->hasMajorViolations());
    }

    public function testHasMajorViolations_withMajorViolations_isTrue()
    {
        $this->report->major($this->violation('test'));
        $this->assertTrue($this->report->hasMajorViolations());
    }

    public function testGetMajorViolations_withMajorViolations_returnsViolations()
    {
        // add violations
        $this->report->major($this->violation('test'));
        $this->report->major($this->violation('test'));
        $this->report->major($this->violation('test'));
        $this->report->major($this->violation('foo'));

        $violations = $this->report->getMajorViolations();
        $this->assertArrayHasKey('test', $violations);
        $this->assertArrayHasKey('foo', $violations);
        $this->assertCount(2, $violations);
        $this->assertCount(3, $violations['test']);
        $this->assertCount(1, $violations['foo']);
    }


    // // TEST MINOR VIOLATIONS // //

    public function testHasMinorViolations_withNoViolations_isFalse()
    {
        $this->assertFalse($this->report->hasMinorViolations());
    }

    public function testHasMinorViolations_withMinorViolations_isTrue()
    {
        $this->report->minor($this->violation('test'));
        $this->assertTrue($this->report->hasMinorViolations());
    }

    public function testGetMinorViolations_withMinorViolations_returnsViolations()
    {
        // add violations
        $this->report->minor($this->violation('test'));
        $this->report->minor($this->violation('test'));
        $this->report->minor($this->violation('test'));
        $this->report->minor($this->violation('foo'));

        $violations = $this->report->getMinorViolations();
        $this->assertArrayHasKey('test', $violations);
        $this->assertArrayHasKey('foo', $violations);
        $this->assertCount(2, $violations);
        $this->assertCount(3, $violations['test']);
        $this->assertCount(1, $violations['foo']);
    }


    // // TEST UNDEFINED VIOLATIONS // //

    public function testHasUndefinedViolations_withNoViolations_isFalse()
    {
        $this->assertFalse($this->report->hasUndefinedViolations());
    }

    public function testHasUndefinedViolations_withUndefinedViolations_isTrue()
    {
        $this->report->undefined($this->violation('test'));
        $this->assertTrue($this->report->hasUndefinedViolations());
    }

    public function testGetUndefinedViolations_withUndefinedViolations_returnsViolations()
    {
        // add violations
        $this->report->undefined($this->violation('test'));
        $this->report->undefined($this->violation('test'));
        $this->report->undefined($this->violation('test'));
        $this->report->undefined($this->violation('foo'));

        $violations = $this->report->getUndefinedViolations();
        $this->assertArrayHasKey('test', $violations);
        $this->assertArrayHasKey('foo', $violations);
        $this->assertCount(2, $violations);
        $this->assertCount(3, $violations['test']);
        $this->assertCount(1, $violations['foo']);
    }


    public function testHasUndefinedViolations_withIgnoreUndefined_isFalse()
    {
        $this->report = new PolicyViolationReport(false);
        $this->report->undefined($this->violation('test'));
        $this->assertFalse($this->report->hasUndefinedViolations());
    }


    // // TEST GENERAL METHODS // //

    public function testHasViolations_withNoViolations_isFalse() {
        $this->assertFalse($this->report->hasViolations());
    }

    public function testHasViolations_withAllViolations_isTrue() {
        $this->report->major($this->violation('test'));
        $this->report->minor($this->violation('test'));
        $this->report->undefined($this->violation('test'));
        $this->assertTrue($this->report->hasViolations());
    }
}