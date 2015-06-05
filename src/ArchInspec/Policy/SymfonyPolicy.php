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

namespace ArchInspec\Policy;

use ArchInspec\Node\NodeInterface;
use ArchInspec\Policy\Evaluation\EvaluationResult;

/**
 * Contains a policy for Symfony projects.
 *
 * @package ArchInspec\Policy
 */
class SymfonyPolicy implements PolicyInterface
{

    const POLICY_NAME = "symfony";

    private $allow = [
        '.*Bundle\\\\Controller' => [
            'Symfony\Component\HttpFoundation',
            'Symfony\Component\HttpKernel',
            'Symfony\Component\Security',
        ],
        '.*Bundle\\\\Form' => [
            'Symfony\Component\OptionsResolver',
            'Symfony\Component\Form',
        ],
        '.*Bundle\\\\EventListener' => [
            'Symfony\Component\EventDispatcher',
        ],
        '.*Bundle\\\\DependencyInjection' => [
            'Symfony\Component\DependencyInjection',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function affects(NodeInterface $from, NodeInterface $to)
    {
        foreach ($this->allow as $regex => $allowed) {
            // check that $from matches
            if ($this->regexMatchesNode($regex, $from)) {
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isAllowed(NodeInterface $from, NodeInterface $to)
    {
        foreach ($this->allow as $regex => $allowed) {
            // check that $from matches
            if (!$this->regexMatchesNode($regex, $from)) {
                continue;
            }

            // check all allowed namespaces
            foreach ($allowed as $namespace) {
                if ($this->namespaceContains($namespace, $to->getFQName())) {
                    return EvaluationResult::allowed();
                }
            }
        }
        return EvaluationResult::undefined();
    }

    private function regexMatchesNode($regex, NodeInterface $node)
    {
        return (bool)preg_match("/{$regex}/i", $node->getFQName());
    }

    protected function namespaceContains($namespace, $other)
    {
        return strlen($namespace) <= strlen($other) && strpos($other, $namespace) === 0;
    }
}