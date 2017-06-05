<?php

namespace Doppy\GoogleTagManagerBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('doppy_gtm');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('enabled')->defaultValue(true)->end()
                ->booleanNode('debug')->defaultValue(false)->end()
                ->scalarNode('tag_id')->required(true)->end()
            ->end();

        return $treeBuilder;
    }
}
