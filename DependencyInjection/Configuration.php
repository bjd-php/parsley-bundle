<?php

namespace JBen87\ParsleyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('jben87_parsley');
        $rootNode
            ->children()
                ->booleanNode('enabled')
                    ->defaultTrue()
                ->end()
                ->scalarNode('trigger_event')
                    ->defaultValue('blur')
                ->end()
                ->arrayNode('date_pattern')
                    ->useAttributeAsKey('locale')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('time_pattern')
                    ->useAttributeAsKey('locale')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('datetime_pattern')
                    ->useAttributeAsKey('locale')
                    ->scalarPrototype()->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
