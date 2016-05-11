<?php
/**
 * This file is part of the "archinspec" project.
 *
 * (c) Fabian Keller <hello@fabian-keller.de>
 *
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
 */

namespace ArchInspec\PhpDA;

use ArchInspec\Inspector\Inspector;
use ArchInspec\Policy\Evaluation\EvaluationResult;
use ArchInspec\Policy\Evaluation\IEvaluationResult;
use ArchInspec\Report\PolicyViolation;
use ArchInspec\Report\ViolationCollectorInterface;
use PhpDA\Reference\ValidatorInterface;
use PhpParser\Node\Name;

class ReferenceValidator implements ValidatorInterface
{
    /**
     * Note: the use of the singleton pattern here is highly discouraged. However, there is currently an issue in the
     * phpDependencyAnalysis library that does not allow to inject any dependencies (either through constructors or
     * setters) into the reference validator. The singelton currently allows to do that, but the dependencies should be
     * properly injected as soon as the issue in phpDA is resolved.
     *
     * @see https://github.com/mamuz/PhpDependencyAnalysis/issues/22
     *
     * @var  ReferenceValidator - holds the singleton instance of the validator.
     */
    private static $instance;

    /** @var Inspector */
    private $inspector;
    /** @var IEvaluationResult */
    private $lastResult;
    /** @var ViolationCollectorInterface */
    private $collector;

    public function __construct()
    {
        if (!is_null(self::$instance)) {
            $this->inspector = self::$instance->inspector;
            $this->collector = self::$instance->collector;
        }
        self::$instance = $this;
    }

    /**
     * @deprecated this method must be replaced in the near future with proper dependency injection techniques! See {@link ReferenceValidator#instance} for more information.
     * @return ReferenceValidator
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Set the inspector to be used for the analysis.
     *
     * @param Inspector $inspector
     */
    public function setInspector($inspector)
    {
        $this->inspector = $inspector;
    }

    public function setViolationCollector(ViolationCollectorInterface $collector)
    {
        $this->collector = $collector;
    }

    /**
     * {@inheritdoc}
     */
    public function isValidBetween(Name $from, Name $to)
    {
        $this->lastResult = $this->inspector->isAllowed($from->toString(), $to->toString());
        if (!is_null($this->collector) && $this->lastResult->isDenied()) {
            $this->collector->report(new PolicyViolation($from, $to, $this->lastResult));
        }
        return $this->lastResult->isAllowed();
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages()
    {
        return [$this->lastResult->getMessage()];
    }
}