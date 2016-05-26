<?php
/**
 * This file is part of the "archinspec" project.
 *
 * (c) Fabian Keller <hello@fabian-keller.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ArchInspec\Application;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

/**
 * Holds all configuration options to run an ArchInspec analysis.
 */
class AIConfig
{
    /** @var string */
    private $source;
    /** @var string */
    private $output;
    /** @var string */
    private $architecture;
    /** @var array */
    private $phpDa;
    /** @var boolean */
    private $reportUndefined = false;


    private function __construct(array $config = [])
    {
        $processor = new Processor();
        $structure = new AIConfigValidationTree();
        $processedConfiguration = $processor->processConfiguration($structure, [$config]);
        foreach ($processedConfiguration as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($this, $setter)) {
                $this->{$setter}($value);
            } elseif (property_exists($this, $key)) {
                $this->{$key} = $value;
            } else {
                throw new \RuntimeException(sprintf("Got config key '%s' which is not supported in the config class! As this should not have happened, please file a bug.",
                    $key));
            }
        }
    }

    /**
     * Creates a config from a file.
     *
     * @param string $configFile path to the config file
     *
     * @return AIConfig
     */
    public static function fromYamlFile($configFile)
    {
        return self::fromYaml(file_get_contents($configFile));
    }

    /**
     * Creates a config from a YAML string.
     *
     * @param string $configString
     *
     * @return AIConfig
     */
    public static function fromYaml($configString)
    {
        return new self(Yaml::parse($configString));
    }

    /**
     * Sets the path to the source files to analyze.
     *
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = realpath($source);
    }

    /**
     * Returns the path to the source files to analyze.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $output
     */
    public function setOutput($output)
    {
        $this->output = realpath($output);
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @return array
     */
    public function getPhpDa()
    {
        return $this->phpDa;
    }

    /**
     * The path to the architecture definition
     *
     * @return string
     */
    public function getArchitecture()
    {
        return $this->architecture;
    }

    /**
     * The path to the architecture definition
     *
     * @param string $architecture
     */
    public function setArchitecture($architecture)
    {
        $this->architecture = $architecture;
    }

    /**
     * Returns true if undefined architecture relations should be reported.
     *
     * @return boolean
     */
    public function getReportUndefined()
    {
        return $this->reportUndefined;
    }

    /**
     * @param boolean $reportUndefined
     */
    public function setReportUndefined($reportUndefined)
    {
        $this->reportUndefined = (bool) $reportUndefined;
    }


}