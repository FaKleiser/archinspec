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

namespace ArchInspec\Policy;

use ArchInspec\Node\NodeInterface;
use ArchInspec\Policy\Evaluation\EvaluationResult;

/**
 * Allows siblings to use each other.
 */
class SiblingPolicy extends AbstractPolicy
{
    /** The name of the policy, as used in architecture description files */
    const POLICY_NAME = "siblings";

    /**
     * {@inheritdoc}
     */
    public function affects(NodeInterface $from, NodeInterface $to)
    {
        return $this->isSibling($from, $to);
    }

    /**
     * {@inheritdoc}
     */
    public function isAllowed(NodeInterface $from, NodeInterface $to)
    {
        if ($this->isSibling($from, $to)) {
            return EvaluationResult::allowed($this);
        }
        return EvaluationResult::undefined();
    }


    /**
     * Determines whether the two given nodes are siblings.
     *
     * @param NodeInterface $from
     * @param NodeInterface $to
     * @return boolean
     */
    private function isSibling(NodeInterface $from, NodeInterface $to)
    {
        return $this->parentNamespace($from) == $this->parentNamespace($to);
    }

    private function parentNamespace(NodeInterface $node) {
        $fqcn = $node->getFQName();
        $lastNsSep = strpos($fqcn, '\\');
        if (false === $lastNsSep) {
            // no namespace
            return "";
        } else {
            return substr($fqcn, 0, $lastNsSep);
        }
    }

}