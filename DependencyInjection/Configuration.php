<?php

namespace Clearcode\ElkBridgeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('elk_bridge');

        $rootNode
            ->canBeEnabled()
            ->children()
                ->scalarNode('logstash_namespace')
                    ->defaultValue('elk')
                ->end()
                ->scalarNode('channel')
                    ->defaultValue('event_bus_elk')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
