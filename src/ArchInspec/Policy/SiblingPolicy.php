<?php
/**
 * This file is part of the "archinspec" project.
 *
 * (c) Fabian Keller <hello@fabian-keller.de>
 *
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
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