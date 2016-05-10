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

namespace ArchInspec\Policy\Factory;

use ArchInspec\Policy\AllowPolicy;
use ArchInspec\Policy\DenyPolicy;
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
    public function factory($name, $target = null, array $options = null)
    {
        if (!$this->supports($name)) {
            throw new \RuntimeException(sprintf("Factory for policies of type %s is not defined!", $name));
        }
        return $this->factories[$name]->factory($name, $target, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function supportedPolicies()
    {
        return array_keys($this->factories);
    }
}