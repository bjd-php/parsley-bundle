<?php

namespace JBen87\ParsleyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

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
     * @inheritdoc
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration($this->name);
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('jben87_parsley.global', $config['global']);
        $container->setParameter('jben87_parsley.trigger_event', $config['trigger_event']);

        $this->setDateTimePatternParameters($config, $container);

        $loader = new XmlFileLoader($container, new FileLocator(sprintf('%s/../Resources/config', __DIR__)));
        $loader->load('factory.xml');
        $loader->load('form.xml');
    }

    /**
     * @inheritdoc
     */
    public function getAlias(): string
    {
        return $this->name;
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     */
    private function setDateTimePatternParameters(array $config, ContainerBuilder $container): void
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

        $container->setParameter('jben87_parsley.date_time_pattern', $dateTimePattern);
        $container->setParameter('jben87_parsley.date_pattern', $datePattern);
        $container->setParameter('jben87_parsley.time_pattern', $timePattern);
    }
}
