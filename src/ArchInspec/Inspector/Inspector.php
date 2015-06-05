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
use ArchInspec\Policy\Evaluation\EvaluationResult;
use ArchInspec\Policy\Factory\PolicyFactory;
use ArchInspec\Policy\Factory\PolicyFactoryInterface;
use ArchInspec\Policy\PolicyInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Parses architecture definition files and can then run queries against the definition to judge whether a connection
 * between two architectural components is allowed by the definition or not.
 *
 * @package ArchInspec\Inspector
 */
class Inspector
{
    /** @var PolicyFactoryInterface the polict factory */
    private $policyFactory = null;

    /** @var Node */
    private $tree;

    /**
     * Creates an inspector instance.
     *
     * Loads the default factory from {@link PolicyFactory::defaultFactory} if no factory is provided.
     *
     * @param PolicyFactoryInterface $policyFactory
     */
    public function __construct(PolicyFactoryInterface $policyFactory = null)
    {
        $this->tree = new Node("*");

        if (is_null($policyFactory)) {
            $policyFactory = PolicyFactory::defaultFactory();
        }
        $this->policyFactory = $policyFactory;
    }

    /**
     * Loads the given architecture definition.
     *
     * Will first try to interpet the string as filename, then as YAML string.
     *
     * @param string $adl
     */
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
        return $this->policyFactory->factory($name, $options);
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

    /**
     * Resolves all hierarchical policies to determine whether the connection between $from and $to is valid.
     *
     * @param string $from
     * @param string $to
     * @return \ArchInspec\Policy\Evaluation\IEvaluationResult
     */
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
                if (!$result->equals(EvaluationResult::undefined())) {
                    return $result;
                }
            }
            $node = $node->getParent();
        }
        return EvaluationResult::undefined();
    }
}