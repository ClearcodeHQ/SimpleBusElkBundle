<?php

namespace Clearcode\ElkBridgeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class ElkBridgeExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/'));
        $loader->load('monolog.yml');

        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $config);

        $container->setParameter('elk_bridge.logstash_namespace', $config['logstash_namespace']);
        $container->setParameter('elk_bridge.channel', $config['channel']);
        $container->setParameter('elk_bridge.enabled', $config['enabled']);

        if ($config['enabled']) {
            $loader->load('event_bus.yml');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'elk_bridge';
    }
}
