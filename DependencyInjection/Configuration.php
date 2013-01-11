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
        /** @var \Symfony\Component\Config\Definition\Builder\NodeDefinition */
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
                    ->scalarNode('transactional_group')->defaultValue(false)->end()
                    ->arrayNode('bcc')
                        ->prototype('scalar')->end()
                    ->end()
                    ->arrayNode('options')
                        ->children()
                            ->scalarNode('ReplyTo')->end()
                            ->scalarNode('BCC')->end()
                            ->scalarNode('CharacterSet')->end()
                            ->scalarNode('Encoding')->end()
                            ->scalarNode('Priority')->end()
                            ->scalarNode('NoDuplicates')->end()
                            ->scalarNode('UseSystemMAILFROM')->end()
                            ->scalarNode('Receipt')->end()
                            ->scalarNode('Wrapping')->end()
                            ->scalarNode('ClickTrack')->end()
                            ->scalarNode('OpenTrack')->end()
                            ->scalarNode('NoClickTrackText')->end()
                            ->scalarNode('SendDate')->end()
                            ->scalarNode('ThrottlingNumberEmails')->end()
                            ->scalarNode('ThrottlingNumberMinutes')->end()
                            ->scalarNode('DoNotSendTo')->end()
                            ->scalarNode('SuppressionGroups')->end()
                            ->scalarNode('Triggers')->end()
                            ->scalarNode('EmbedImages')->end()
                            ->scalarNode('Attachment1')->end()
                            ->scalarNode('Attachment2')->end()
                            ->scalarNode('Attachment3')->end()
                            ->scalarNode('Attachment4')->end()
                            ->scalarNode('Attachment5')->end()
                            ->scalarNode('SMS')->end()
                            ->scalarNode('Template')->end()
                            ->scalarNode('CustomCampaignID')->end()
                            ->scalarNode('PreprocessNow')->end()
                            ->scalarNode('PreprocessOnly')->end()
                            ->scalarNode('TransactionalGroupID')->end()
                            ->scalarNode('CC')->end()
                            ->scalarNode('SkipUnsubCheck')->end()
                        ->end()
                    ->end()
                ->end()
        ;
        return $treeBuilder;
    }

}
