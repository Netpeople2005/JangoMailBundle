<?php

namespace Netpeople\JangoMailBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('jango_mail');
        /*
         * Definimos los parametros de configuración que se especificarán
         * para trabajar con jango_mail
         */
        $rootNode
                ->children()
                    ->scalarNode('username')->end()
                    ->scalarNode('password')->end()
                    ->scalarNode('fromemail')->end()
                    ->scalarNode('fromname')->end()
                    ->scalarNode('disable_delivery')->defaultValue(false)->end()
                    ->scalarNode('enable_log')->defaultValue(false)->end()
                    ->arrayNode('bcc')
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
        ;
        return $treeBuilder;
    }

}
