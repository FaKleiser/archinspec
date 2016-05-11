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
use ArchInspec\Policy\Evaluation\IEvaluationResult;

/**
 * Used to design policies that allow or deny certain architectural constellations.
 *
 * @package ArchInspec\Policy
 */
interface PolicyInterface
{
    /**
     * Determines whether the policy can be applied to the connection between $from and $to.
     *
     * @param NodeInterface $from
     * @param NodeInterface $to
     * @return boolean true if the policy is applicable, false otherwise
     */
    public function affects(NodeInterface $from, NodeInterface $to);

    /**
     * Evaluates the policies and determines the status of the connection between $from and $to.
     *
     * @param NodeInterface $from
     * @param NodeInterface $to
     * @return IEvaluationResult
     */
    public function isAllowed(NodeInterface $from, NodeInterface $to);

    /**
     * If available, returns a reason WHY the policy exists.
     *
     * @return null|string
     */
    public function getReason();
}