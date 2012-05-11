<?php

namespace EricClemmons\Bundle\StaticBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('eric_clemmons_static');

        $rootNode
            ->children()
                ->scalarNode('articles')->defaultValue('%kernel.root_dir%/Resources/articles')->end()
                ->scalarNode('pages')->defaultValue('%kernel.root_dir%/Resources/pages')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
