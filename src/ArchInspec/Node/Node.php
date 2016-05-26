<?php
/**
 * This file is part of the "archinspec" project.
 *
 * (c) Fabian Keller <hello@fabian-keller.de>
 *
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
 */

namespace ArchInspec\Node;

use ArchInspec\Policy\PolicyInterface;

/**
 * Represents an architectural node that is used to build the architectural tree. Holds attached policies.
 *
 * @package ArchInspec\Node
 */
class Node implements NodeInterface
{
    /** @var string the name of the node */
    private $name;
    /** @var string the fully qualified name of the node */
    private $fqname;
    /** @var NodeInterface the parent node */
    private $parent;
    /** @var NodeInterface[] children of this node */
    private $children = [];

    /** @var PolicyInterface[] */
    private $policies = [];

    /**
     * Creates a new node.
     *
     * @param string $name name of the node, expects a namespace part - not the fully qualified namespace
     * @param NodeInterface $parent optional parent node
     */
    public function __construct($name, $parent = null)
    {
        $this->name = $name;
        $this->parent = $parent;
    }

    /**
     * Adds a child to the current node.
     *
     * @param NodeInterface $child
     */
    public function addChild(NodeInterface $child)
    {
        $this->children[$child->getName()] = $child;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getFQName()
    {
        if (is_null($this->fqname)) {
            $el = $this;
            $fqname = "";
            while ($el->hasParent()) {
                $fqname = '\\' . $el->getName() . $fqname;
                $el = $el->getParent();
            }
            $this->fqname = ltrim($fqname, '\\');
        }
        return $this->fqname;
    }

    /**
     * {@inheritdoc}
     */
    public function hasParent()
    {
        return !is_null($this->parent);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function hasChildren()
    {
        return count($this->children) > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        return array_values($this->children);
    }

    /**
     * {@inheritdoc}
     */
    public function hasChild($name)
    {
        return isset($this->children[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getChild($name)
    {
        return $this->children[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function attachPolicy(PolicyInterface $policy)
    {
        $this->policies[] = $policy;
    }

    /**
     * {@inheritdoc}
     */
    public function getPolicies()
    {
        return $this->policies;
    }


}