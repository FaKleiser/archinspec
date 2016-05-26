<?php
/**
 * This file is part of the "archinspec" project.
 *
 * (c) Fabian Keller <hello@fabian-keller.de>
 *
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
 */

namespace ArchInspec\Policy\Factory;

/**
 * Creates policies by passing the options to the policy class constructor.
 */
class ConstructorFactory extends AbstractPolicyFactory
{
    /** @var string */
    private $policyClassName;
    /** @var string[] */
    private $names;

    /**
     * Sets the class name of the policy to instantiate on factory invocation.
     *
     * @param string|string[] $names the name of the policy
     * @param string $policyClassName
     */
    public function __construct($names, $policyClassName)
    {
        if (!is_array($names)) {
            $names = [$names];
        }
        $this->names = $names;
        $this->policyClassName = $policyClassName;
    }

    /**
     * {@inheritdoc}
     */
    public function factory($name, $target = null, array $options = null, $rationale = null)
    {
        if (!in_array($name, $this->supportedPolicies())) {
            throw new \RuntimeException(sprintf("This ConstructorFactory can only factory policies of types [%s], but type %s was requested!", join(",", $this->names), $name));
        }
        $class = $this->policyClassName;
        return new $class($target, $options, $rationale);
    }


    /**
     * {@inheritdoc}
     */
    public function supportedPolicies()
    {
        return $this->names;
    }
}