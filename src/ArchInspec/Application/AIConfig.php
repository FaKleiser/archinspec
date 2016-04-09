<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Fabian Keller
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
            }
            throw new \RuntimeException(sprintf("Got config key '%s' which is not supported in the config class! As this should not have happened, please file a bug.", $key));
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
        if (!is_readable($source)) {
            throw new \InvalidArgumentException("The given source path does either not exist or is not readable: " . $source);
        }
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
        if (!is_dir($output)) {
            throw new \InvalidArgumentException("The given output path does not exist: " . $output);
        }
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
        if (!is_readable($architecture)) {
            throw new \InvalidArgumentException("The given architecture file does not exist or is not readable: " . $architecture);
        }
        $this->architecture = $architecture;
    }
}