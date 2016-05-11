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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Defines the structure of all valid {@link AIConfig} configurations.
 */
class AIConfigValidationTree implements ConfigurationInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('archinspec');

        // @formatter:off
        $root
            ->children()
                ->scalarNode('source')->end()
                ->scalarNode('output')->end()
                ->scalarNode('architecture')->end()
                ->arrayNode('phpDa')
                    ->children()
                        ->scalarNode('filePattern')->defaultValue('*.php')->end()
                        ->arrayNode('ignore')
                            ->defaultValue(['Tests', 'Behat'])
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        // @formatter:on

        return $treeBuilder;
    }
}