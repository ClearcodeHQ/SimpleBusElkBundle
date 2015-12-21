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
            ->children()
                ->booleanNode('enable_simple_bus_middleware')
                    ->defaultValue(true)
                ->scalarNode('logstash_namespace')
                    ->defaultValue('elk')
                ->end()
                ->scalarNode('monolog_channel')
                    ->defaultValue('event_bus_elk')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
