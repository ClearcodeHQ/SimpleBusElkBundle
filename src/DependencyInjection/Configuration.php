<?php

namespace Clearcode\SimpleBusElkBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('simple_bus_elk');

        $rootNode
            ->children()
                ->booleanNode('middleware')
                    ->defaultValue(true)
                ->scalarNode('logstash_namespace')
                    ->defaultValue('elk')
                ->end()
                ->scalarNode('monolog_channel')
                    ->defaultValue('simple_bus_elk')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
