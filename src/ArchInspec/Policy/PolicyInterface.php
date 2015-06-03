<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Fabian Keller
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

namespace ArchInspec\Policy;

use ArchInspec\Node\NodeInterface;

/**
 * Used to design policies that allow or deny certain architectural constellations.
 *
 * @package ArchInspec\Policy
 */
interface PolicyInterface
{
    /**
     * Determines whether the policy can be applied to the connection between $from and $to.
     *
     * @param NodeInterface $from
     * @param NodeInterface $to
     * @return boolean true if the policy is applicable, false otherwise
     */
    public function affects(NodeInterface $from, NodeInterface $to);

    /**
     * Evaluates the policies and determines the status of the connection between $from and $to.
     *
     * @param NodeInterface $from
     * @param NodeInterface $to
     * @return boolean true if the connection is allowed by this policy, false otherwise
     */
    public function isAllowed(NodeInterface $from, NodeInterface $to);
}