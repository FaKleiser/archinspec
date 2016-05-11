<?php
/**
 * This file is part of the "archinspec" project.
 *
 * (c) Fabian Keller <hello@fabian-keller.de>
 *
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
 */

namespace ArchInspec\Inspector;

use ArchInspec\Node\Node;
use ArchInspec\Node\NodeInterface;
use ArchInspec\Policy\Evaluation\EvaluationResult;
use ArchInspec\Policy\Factory\PolicyFactory;
use ArchInspec\Policy\Factory\PolicyFactoryInterface;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Yaml\Yaml;

/**
 * Parses architecture definition files and can then run queries against the definition to judge whether a connection
 * between two architectural components is allowed by the definition or not.
 *
 * @package ArchInspec\Inspector
 */
class Inspector
{
    /** @var PolicyFactoryInterface the policy factory */
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
            foreach ($policies as $options) {
                // get policy name and target
                if (isset($options['policy'])) {
                    $policyName = $options['policy'];
                    $target = null;
                    if (isset($options['target'])) {
                        $target = $options['target'];
                        unset($options['target']);
                    }
                    unset($options['policy']);
                } else {
                    $policyName = $this->guessPolicyName($options);
                    $target = $options[$policyName];
                }

                // policy reasoning
                $reason = null;
                if (isset($options['reason'])) {
                    $reason = $options['reason'];
                    unset($options['reason']);
                }

                $node->attachPolicy($this->policyFactory->factory($policyName, $target, $options, $reason));
            }
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

    /**
     * Tries to guess the intended policy name based on the available option keys.
     *
     * @param mixed[] $options
     * @throws \RuntimeException In case the policy cannot be guessed
     * @return string
     */
    private function guessPolicyName($options)
    {
        foreach (array_keys($options) as $potentialPolicy) {
            if ($this->policyFactory->supports($potentialPolicy)) {
                return $potentialPolicy;
            }
        }
        throw new RuntimeException(sprintf("None of the provided options [%s] refers to a supported policy [%s]!",
            join(",", array_keys($options)),
            join(",", $this->policyFactory->supportedPolicies())));
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
                if (!$policy->affects($from, $to)) {
                    continue;
                }

                // only return if the result is not undefined
                $result = $policy->isAllowed($from, $to);
                if (!$result->isUndefined()) {
                    return $result;
                }
            }
            $node = $node->getParent();
        }
        return EvaluationResult::undefined();
    }

}