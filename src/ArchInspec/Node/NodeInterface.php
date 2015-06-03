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