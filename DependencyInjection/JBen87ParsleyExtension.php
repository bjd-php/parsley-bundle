<?php

namespace JBen87\ParsleyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class JBen87ParsleyExtension extends ConfigurableExtension
{
    /**
     * @inheritdoc
     */
    public function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $container->setParameter('jben87_parsley.enabled', $mergedConfig['enabled']);
        $container->setParameter('jben87_parsley.trigger_event', $mergedConfig['trigger_event']);

        $this->setDateTimePatternParameters($mergedConfig, $container);

        $loader = new XmlFileLoader($container, new FileLocator(sprintf('%s/../Resources/config', __DIR__)));
        $loader->load('factory.xml');
        $loader->load('form.xml');
    }

    /**
     * @param array $mergedConfig
     * @param ContainerBuilder $container
     */
    private function setDateTimePatternParameters(array $mergedConfig, ContainerBuilder $container): void
    {
        $locale = $container->getParameter('locale');

        $datePattern = '\d{4}-\d{2}-\d{2}';
        if (true === isset($mergedConfig['date_pattern'][$locale])) {
            $datePattern = $mergedConfig['date_pattern'][$locale];
        }

        $timePattern = '\d{2}:\d{2}';
        if (true === isset($mergedConfig['time_pattern'][$locale])) {
            $timePattern = $mergedConfig['time_pattern'][$locale];
        }

        $dateTimePattern = sprintf('%s %s', $datePattern, $timePattern);
        if (true === isset($mergedConfig['datetime_pattern'][$locale])) {
            $dateTimePattern = $mergedConfig['datetime_pattern'][$locale];
        }

        $container->setParameter('jben87_parsley.date_pattern', $datePattern);
        $container->setParameter('jben87_parsley.time_pattern', $timePattern);
        $container->setParameter('jben87_parsley.datetime_pattern', $dateTimePattern);
    }
}
