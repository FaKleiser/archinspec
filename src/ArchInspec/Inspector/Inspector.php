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

namespace ArchInspec\Inspector;

use ArchInspec\Node\Node;
use ArchInspec\Node\NodeInterface;
use ArchInspec\Policy\AllowPolicy;
use ArchInspec\Policy\DenyPolicy;
use ArchInspec\Policy\PolicyInterface;
use Symfony\Component\Yaml\Yaml;

class Inspector
{
    /** @var Node */
    private $tree;

    public function __construct()
    {
        $this->tree = new Node("*");
    }

    public function load($adl)
    {
        if (file_exists($adl)) {
            $adl = file_get_contents($adl);
        }
        $yaml = Yaml::parse($adl);

        // create nodes
        foreach ($yaml as $fqns => $policies) {
            $node = $this->getOrCreateNode($fqns);
            // attach policies to node
            foreach ($policies as $policy => $options) {
                $node->attachPolicy($this->factoryPolicy($policy, $options));
            }
        }
    }

    /**
     * Creates an instance of the $name policy with the given $options.
     *
     * @param string $name
     * @param array $options
     *
     * @return PolicyInterface
     */
    private function factoryPolicy($name, $options)
    {
        switch ($name) {
            case 'allow':
                return new AllowPolicy($options);
            case 'deny':
                return new DenyPolicy($options);
            default:
                throw new \RuntimeException("Cannot create unknown policy {$name}!");
        }
    }

    /**
     * Either returns a node or creates it.
     *
     * @param string $name
     *
     * @return NodeInterface the last element
     */
    private function getOrCreateNode($name)
    {
        $parts = explode("\\", $name);
        $parent = $this->tree;
        foreach ($parts as $part) {
            if (!$parent->hasChild($part)) {
                $node = new Node($part, $parent);
                $parent->addChild($node);
                $parent = $node;
            } else {
                $parent = $parent->getChild($part);
            }
        }
        return $parent;
    }

    public function isAllowed($from, $to)
    {
        $to = $this->getOrCreateNode($to);
        $from = $this->getOrCreateNode($from);
        $node = $from;
        while (!is_null($node)) {
            foreach ($node->getPolicies() as $policy) {
                // check that policy affects $to
                if (!$policy->affects($node, $to)) {
                    continue;
                }

                // only return if the result is not undefined
                $result = $policy->isAllowed($from, $to);
                if (!is_null($result)) {
                    return $result;
                }
            }
            $node = $node->getParent();
        }
        return null;
    }

}