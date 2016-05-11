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

/**
 * Provides a common implementation for {@link PolicyInterface}s.
 */
abstract class AbstractPolicy implements PolicyInterface
{
    /** @var string[] a list of targets to which the policy is applied */
    private $targets;
    /** @var mixed[] a map of options */
    private $options;
    /** @var string the reason why this policy exists */
    private $reason;

    public function __construct($targets = [], $options = null, $reason = null)
    {
        if (!is_array($targets)) {
            $targets = [$targets];
        }
        $this->targets = $targets;
        if (!is_null($options)) {
            $this->options = $options;
        }
        $this->reason = $reason;
    }

    /**
     * Returns the targets affected by this policy.
     *
     * @return string[]
     */
    protected function getTargets()
    {
        return $this->targets;
    }

    /**
     * Returns the options set for this policy
     *
     * @return \mixed[]
     */
    protected function getOptions()
    {
        return $this->options;
    }

    /**
     * Determines whether the policy has the option $key set.
     *
     * @param string $key
     * @return \mixed[]|null
     */
    protected function hasOption($key)
    {
        return array_key_exists($key, $this->options);
    }

    /**
     * Returns the value for the option $key.
     *
     * @param string $key
     * @return mixed
     */
    protected function getOption($key)
    {
        return $this->options[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Checks whether a namespace is contained within another. That is, given the tree structure of all namespace, the
     * namespace $namespace is an ancestor of the namespace $other.
     *
     * @param string $namespace
     * @param string $other
     * @return bool true if $namespace contains $other
     */
    protected function namespaceContains($namespace, $other)
    {
        return strlen($namespace) <= strlen($other) && strpos($other, $namespace) === 0;
    }
}