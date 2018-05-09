<?php

namespace JBen87\ParsleyBundle\Tests;

use JBen87\ParsleyBundle\DependencyInjection\Configuration;
use JBen87\ParsleyBundle\JBen87ParsleyBundle;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class JBen87ParsleyBundleTest extends TestCase
{
    public function testContainerExtension(): void
    {
        $container = $this->createMock(ContainerBuilder::class);
        $this->setUpContainer($container);

        $container
            ->expects($this->exactly(5))
            ->method('setParameter')
            ->withConsecutive(
                ['jben87_parsley.enabled', true],
                ['jben87_parsley.trigger_event', 'blur'],
                ['jben87_parsley.date_pattern', '\d{4}-\d{2}-\d{2}'],
                ['jben87_parsley.time_pattern', '\d{2}:\d{2}'],
                ['jben87_parsley.datetime_pattern', '\d{4}-\d{2}-\d{2} \d{2}:\d{2}']
            )
        ;

        $bundle = new JBen87ParsleyBundle();
        $extension = $bundle->getContainerExtension();
        $extension->load([], $container);
    }

    public function testContainerExtensionAlternative(): void
    {
        $container = $this->createMock(ContainerBuilder::class);
        $this->setUpContainer($container);

        $container
            ->expects($this->exactly(5))
            ->method('setParameter')
            ->withConsecutive(
                ['jben87_parsley.enabled', false],
                ['jben87_parsley.trigger_event', 'click'],
                ['jben87_parsley.date_pattern', '\d{2}/\d{2}/\d{4}'],
                ['jben87_parsley.time_pattern', '\d{2}:\d{2}'],
                ['jben87_parsley.datetime_pattern', '\d{2}/\d{2}/\d{4} \d{2}:\d{2}']
            )
        ;

        $bundle = new JBen87ParsleyBundle();
        $extension = $bundle->getContainerExtension();
        $extension->load(
            [
                'jben87_parsley' => [
                    'enabled' => false,
                    'trigger_event' => 'click',
                    'date_pattern' => ['fr' => '\d{2}/\d{2}/\d{4}'],
                    'time_pattern' => ['fr' => '\d{2}:\d{2}'],
                    'datetime_pattern' => ['fr' => '\d{2}/\d{2}/\d{4} \d{2}:\d{2}'],
                ],
            ],
            $container
        );
    }

    /**
     * @param MockObject $container
     */
    private function setUpContainer(MockObject $container): void
    {
        $container
            ->expects($this->once())
            ->method('getReflectionClass')
            ->with(Configuration::class)
            ->willReturn(new \ReflectionClass(Configuration::class))
        ;

        $container
            ->expects($this->once())
            ->method('getParameter')
            ->with('locale')
            ->willReturn('fr')
        ;
    }
}
