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

use ArchInspec\Policy\AllowPolicy;
use ArchInspec\Policy\DenyPolicy;
use ArchInspec\Policy\SiblingPolicy;
use ArchInspec\Policy\SymfonyPolicy;

/**
 * Class PolicyFactory
 *
 * @package ArchInspec\Policy\Factory
 */
class PolicyFactory extends AbstractPolicyFactory
{
    /** @var PolicyFactoryInterface[] */
    private $factories = [];

    /**
     * Creates a new and empty delegating policy factory.
     */
    public function __construct()
    {
    }

    /**
     * Creates a default policy factory for policies that are shipped with ArchInspec
     *
     * @return PolicyFactoryInterface
     */
    public static function defaultFactory()
    {
        $factory = new static();
        $factory->addFactory(new ConstructorFactory(AllowPolicy::POLICY_NAME, AllowPolicy::class));
        $factory->addFactory(new ConstructorFactory(DenyPolicy::POLICY_NAME, DenyPolicy::class));
        $factory->addFactory(new ConstructorFactory(SiblingPolicy::POLICY_NAME, SiblingPolicy::class));
        $factory->addFactory(new ConstructorFactory(SymfonyPolicy::POLICY_NAME, SymfonyPolicy::class));
        return $factory;
    }

    /**
     * Adds a {@link PolicyFactoryInterface}. Will overwrite existing factories for the same policy.
     *
     * @param PolicyFactoryInterface $factory
     */
    public function addFactory(PolicyFactoryInterface $factory)
    {
        foreach ($factory->supportedPolicies() as $policy) {
            $this->factories[$policy] = $factory;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function factory($name, $target = null, array $options = null, $rationale = null)
    {
        if (!$this->supports($name)) {
            throw new \RuntimeException(sprintf("Factory for policies of type %s is not defined!", $name));
        }
        return $this->factories[$name]->factory($name, $target, $options, $rationale);
    }

    /**
     * {@inheritdoc}
     */
    public function supportedPolicies()
    {
        return array_keys($this->factories);
    }
}