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

class AllowPolicy implements PolicyInterface
{
    /** @var string[] namespaces this policy defines */
    private $namespaces = [];

    public function __construct($namespaces = [])
    {
        $this->namespaces = $namespaces;
    }

    /**
     * Returns true if $other is part of $namespace
     *
     * @param string $namespace
     * @param string $other
     *
     * @return bool
     */
    private function namespaceContains($namespace, $other)
    {
        return strlen($namespace) <= strlen($other) && strpos($other, $namespace) === 0;
    }

    /**
     * {@inheritdoc}
     */
    public function affects(NodeInterface $from, NodeInterface $to)
    {
        foreach ($this->namespaces as $namespace) {
            if ($this->namespaceContains($namespace, $to->getFQName())) {
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isAllowed(NodeInterface $from, NodeInterface $to)
    {
        foreach ($this->namespaces as $namespace) {
            if ($this->namespaceContains($namespace, $to->getFQName())) {
                return true;
            }
        }
        return null;
    }
}