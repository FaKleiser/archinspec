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
 * Implements common methods for a {@link PolicyFactoryInterface}.
 */
abstract class AbstractPolicyFactory implements PolicyFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports($name)
    {
        return in_array($name, $this->supportedPolicies());
    }

}