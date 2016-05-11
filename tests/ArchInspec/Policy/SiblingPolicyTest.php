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

use ArchInspec\Node\NodeInterface;
use ArchInspec\Policy\SiblingPolicy;

class SiblingPolicyTest extends \PHPUnit_Framework_TestCase
{
    /** @var SiblingPolicy */
    private $policy;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $from;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $to;

    protected function setUp()
    {
        $this->policy = new SiblingPolicy();

        $this->from = $this->getMock(NodeInterface::class);
        $this->to = $this->getMock(NodeInterface::class);
    }

    public function testIsAffected_withSiblings_isTrue()
    {
        $this->from->expects($this->once())->method('getFQName')->willReturn('Sample\Namespaced\Class');
        $this->to->expects($this->once())->method('getFQName')->willReturn('Sample\Namespaced\OtherClass');
        $this->assertTrue($this->policy->affects($this->from, $this->to));
    }

    public function testIsAffected_withNoSiblings_isFalse()
    {
        $this->from->expects($this->once())->method('getFQName')->willReturn('Sample\Namespaced\Class');
        $this->to->expects($this->once())->method('getFQName')->willReturn('NoWay\Namespaced\OtherClass');
        $this->assertFalse($this->policy->affects($this->from, $this->to));
    }

    public function testIsAffected_withoutNamespace_isTrue()
    {
        $this->from->expects($this->once())->method('getFQName')->willReturn('Class');
        $this->to->expects($this->once())->method('getFQName')->willReturn('OtherClass');
        $this->assertTrue($this->policy->affects($this->from, $this->to));
    }

    public function testIsAffected_withoutAndWithNamespace_isFalse()
    {
        $this->from->expects($this->once())->method('getFQName')->willReturn('Class');
        $this->to->expects($this->once())->method('getFQName')->willReturn('OtherClass\Class');
        $this->assertFalse($this->policy->affects($this->from, $this->to));
    }
}