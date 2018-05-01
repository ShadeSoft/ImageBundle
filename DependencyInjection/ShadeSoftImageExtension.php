<?php

namespace ShadeSoft\ImageBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ShadeSoftImageExtension extends Extension {

    public function load(array $configs, ContainerBuilder $container) {
        $config = $this->processConfiguration(new Configuration, $configs);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yml');

        $container->getDefinition('shadesoft_image.twig_filter')
            ->addMethodCall('setConfig', array($config['cache_directory']));
    }
}