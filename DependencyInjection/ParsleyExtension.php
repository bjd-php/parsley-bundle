<?php

namespace JBen87\ParsleyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class ParsleyExtension extends Extension
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
     *
     * @codeCoverageIgnore
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration($this->name);
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('jben87_parsley.global', $config['global']);
        $container->setParameter('jben87_parsley.trigger_event', $config['trigger_event']);

        $this->setDateTimePatternParameters($config, $container);

        $loader = new XmlFileLoader($container, new FileLocator(sprintf('%s/../Resources/config', __DIR__)));
        $loader->load('builder.xml');
        $loader->load('factory.xml');
        $loader->load('form.xml');
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return $this->name;
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     */
    private function setDateTimePatternParameters(array $config, ContainerBuilder $container)
    {
        $locale = $container->getParameter('locale');
        $datePattern = '\d{4}-\d{2}-\d{2}';
        $timePattern = '\d{2}:\d{2}';
        $dateTimePattern = sprintf('%s %s', $datePattern, $timePattern);

        if (isset($config['date_time_pattern'][$locale])) {
            $dateTimePattern = $config['date_time_pattern'][$locale];
        }

        if (isset($config['date_pattern'][$locale])) {
            $datePattern = $config['date_pattern'][$locale];
        }

        if (isset($config['time_pattern'][$locale])) {
            $timePattern = $config['time_pattern'][$locale];
        }

        $container->setParameter('jben87_parsley.date_time_format', $dateTimePattern);
        $container->setParameter('jben87_parsley.date_format', $datePattern);
        $container->setParameter('jben87_parsley.time_format', $timePattern);
    }
}
