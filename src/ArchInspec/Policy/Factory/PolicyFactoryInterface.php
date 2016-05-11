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

use ArchInspec\Policy\PolicyInterface;

/**
 * Used to create {@link PolicyInterface} factories.
 */
interface PolicyFactoryInterface
{
    /**
     * Factories the $name policy with the given $options.
     *
     * @param string $name the name of the policy to create
     * @param null|string|string[] $target the targets to apply the policy to
     * @param mixed[] $options
     * @param string $reason
     *
     * @return PolicyInterface
     */
    public function factory($name, $target = null, array $options = null, $reason = null);

    /**
     * Reveals which policies the factory is able to instantiate
     *
     * @return string[] a list of policy names supported by this factory.
     */
    public function supportedPolicies();

    /**
     * Determines whether creating the policy $name is supported by this factory.
     *
     * @param $name
     *
     * @return boolean true if supported
     */
    public function supports($name);
}