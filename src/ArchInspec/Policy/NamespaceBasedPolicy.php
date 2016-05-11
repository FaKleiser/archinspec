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

/**
 * Defines an abstract policy class that takes a set of namespaces as argument.
 */
abstract class NamespaceBasedPolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    public function affects(NodeInterface $from, NodeInterface $to)
    {
        foreach ($this->getTargets() as $namespace) {
            if ($this->namespaceContains($namespace, $to->getFQName())) {
                return true;
            }
        }
        return false;
    }
}