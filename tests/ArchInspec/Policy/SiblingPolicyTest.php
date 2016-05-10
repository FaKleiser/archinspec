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