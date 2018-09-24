<?php

namespace ShadeSoft\ImageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
        $root = $builder->root('shade_soft_image');

        $root
            ->children()
                ->scalarNode('cache_directory')->defaultNull()->end()
            ->end();

        return $builder;
    }
}
