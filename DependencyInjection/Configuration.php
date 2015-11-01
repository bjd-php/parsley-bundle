<?php

namespace JBen87\ParsleyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('jben87_parsley');
        $rootNode
            ->children()
                ->scalarNode('trigger_event')
                    ->defaultValue('blur')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
