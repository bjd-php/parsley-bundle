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
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root($this->name);
        $rootNode
            ->children()
                ->scalarNode('trigger_event')
                    ->defaultValue('blur')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
