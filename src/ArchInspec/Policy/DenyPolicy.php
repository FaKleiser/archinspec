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
use ArchInspec\Policy\Evaluation\EvaluationResult;

/**
 * Policy used to explicitly deny certain namespaces to be used.
 *
 * @package ArchInspec\Policy
 */
class DenyPolicy extends NamespaceBasedPolicy
{
    /**
     * {@inheritdoc}
     */
    public function isAllowed(NodeInterface $from, NodeInterface $to)
    {
        foreach ($this->namespaces as $namespace) {
            if ($this->namespaceContains($namespace, $to->getFQName())) {
                return EvaluationResult::denied(sprintf(
                    "%s has an attached DenyPolicy of namespace %s, preventing use of %s",
                    $from->getFQName(),
                    $namespace,
                    $to->getFQName()
                ));
            }
        }
        return EvaluationResult::undefined();
    }
}