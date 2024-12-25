<?php
/*
namespace App\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('file_templates');

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->useAttributeAsKey('service')
            ->arrayPrototype()
            ->children()
            ->scalarNode('template')->isRequired()->end()
            ->arrayNode('variables')
            ->children()
            ->scalarNode('fileName')->isRequired()->end()
            ->scalarNode('routePath')->isRequired()->end()
            ->scalarNode('routeName')->isRequired()->end()
            ->scalarNode('entity')->isRequired()->end()
            ->scalarNode('redirectRouteName')->isRequired()->end()
            ->scalarNode('templatePath')->isRequired()->end()
            ->arrayNode('fields')
            ->scalarPrototype()->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}