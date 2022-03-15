<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class JBen87ParsleyExtension extends Extension
{
    private string $alias;

    public function __construct(string $alias)
    {
        $this->alias = $alias;
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration($this->alias), $configs);

        $container->setParameter('jben87_parsley.enabled', $config['enabled']);
        $container->setParameter('jben87_parsley.trigger_event', $config['trigger_event']);

        $this->setDateTimePatternParameters($config, $container);

        $loader = new XmlFileLoader($container, new FileLocator(sprintf('%s/../Resources/config', __DIR__)));
        $loader->load('services.xml');
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    private function setDateTimePatternParameters(array $config, ContainerBuilder $container): void
    {
        $locale = $container->getParameter('locale');

        $datePattern = '\d{4}-\d{2}-\d{2}';
        if (true === array_key_exists($locale, $config['date_pattern'])) {
            $datePattern = $config['date_pattern'][$locale];
        }

        $timePattern = '\d{2}:\d{2}';
        if (true === array_key_exists($locale, $config['time_pattern'])) {
            $timePattern = $config['time_pattern'][$locale];
        }

        $dateTimePattern = sprintf('%s %s', $datePattern, $timePattern);
        if (true === array_key_exists($locale, $config['datetime_pattern'])) {
            $dateTimePattern = $config['datetime_pattern'][$locale];
        }

        $container->setParameter('jben87_parsley.date_pattern', $datePattern);
        $container->setParameter('jben87_parsley.time_pattern', $timePattern);
        $container->setParameter('jben87_parsley.datetime_pattern', $dateTimePattern);
    }
}
