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

use ArchInspec\Node\NodeInterface;
use ArchInspec\Policy\Evaluation\EvaluationResult;

/**
 * Contains a policy for Symfony projects.
 */
class SymfonyPolicy extends AbstractPolicy
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
                    return EvaluationResult::allowed($this);
                }
            }
        }
        return EvaluationResult::undefined();
    }

    private function regexMatchesNode($regex, NodeInterface $node)
    {
        return (bool)preg_match("/{$regex}/i", $node->getFQName());
    }
}