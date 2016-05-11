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
 * Represents an architectural element.
 *
 * @package ArchInspec\Node
 */
interface NodeInterface
{
    /**
     * A string representation of the architectural node relative to its parent.
     *
     * Example: This method would return "Space" for a node with namespace "ArchInspec\Long\Name\Space"
     *
     * @return string
     */
    public function getName();

    /**
     * A fully qualified string representation of the architectural node.
     *
     * Example: "ArchInspec\Long\Name\Space"
     *
     * @return string
     */
    public function getFQName();

    /**
     * Return true if the node has a parent, false otherwise
     *
     * @return boolean
     */
    public function hasParent();

    /**
     * Returns the parent element.
     *
     * @return NodeInterface
     */
    public function getParent();

    /**
     * Returns whether the node has any children.
     *
     * @return boolean true if the node has children, false otherwise
     */
    public function hasChildren();

    /**
     * Returns the children of the node.
     *
     * @return NodeInterface[]
     */
    public function getChildren();

    /**
     * Returns true if the node has the given child. Assumes that a relative identifier is given. Will not determine
     * whether the child exists in any subtrees.
     *
     * @param string $name
     *
     * @return boolean true if $name is a direct descendant of this node, false otherwise
     */
    public function hasChild($name);

    /**
     * Returns a direct descendant of this node. Assumes that a relative identifier is given.
     *
     * @param string $name
     *
     * @return NodeInterface the direct descendant with the given identifier
     */
    public function getChild($name);

    /**
     * Attaches the given policy to this node.
     *
     * @param PolicyInterface $policy
     *
     * @return void
     */
    public function attachPolicy(PolicyInterface $policy);

    /**
     * Returns all policies attached to this node.
     *
     * @return PolicyInterface[]
     */
    public function getPolicies();
}