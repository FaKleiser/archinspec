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