<?php

namespace Clearcode\SimpleBusElkBundle\DependencyInjection;

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
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $config);

        $container->setParameter('simple_bus_elk.logstash_namespace', $config['logstash_namespace']);
        $container->setParameter('simple_bus_elk.monolog_channel', $config['monolog_channel']);
        $container->setParameter('simple_bus_elk.middleware', $config['middleware']);

        if ($config['middleware']) {
            $loader->load('logstash.yml');
            $loader->load('middlewares.yml');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'simple_bus_elk';
    }
}
