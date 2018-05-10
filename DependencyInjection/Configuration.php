<?php

namespace JBen87\ParsleyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @var string
     */
    private $alias;

    /**
     * @param string $alias
     */
    public function __construct(string $alias)
    {
        $this->alias = $alias;
    }

    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root($this->alias);
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
