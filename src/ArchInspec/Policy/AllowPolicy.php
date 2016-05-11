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
 * Policy used to explicitly allow certain namespaces to be used.
 *
 * @package ArchInspec\Policy
 */
class AllowPolicy extends NamespaceBasedPolicy
{
    /** The name of the policy, as used in architecture description files */
    const POLICY_NAME = "allow";

    /**
     * {@inheritdoc}
     */
    public function isAllowed(NodeInterface $from, NodeInterface $to)
    {
        foreach ($this->getTargets() as $namespace) {
            if ($this->namespaceContains($namespace, $to->getFQName())) {
                return EvaluationResult::allowed($this);
            }
        }
        return EvaluationResult::undefined();
    }
}