<?php

namespace JBen87\ParsleyBundle\Tests\Form\Extension;

use JBen87\ParsleyBundle\Constraint\Factory\DateFactory;
use JBen87\ParsleyBundle\Constraint\Factory\FactoryRegistry;
use JBen87\ParsleyBundle\Constraint\Factory\RequiredFactory;
use JBen87\ParsleyBundle\Constraint\Reader\ReaderRegistry;
use JBen87\ParsleyBundle\Form\Extension\ParsleyTypeExtension;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ParsleyTypeExtensionTest extends TestCase
{
    /**
     * @var LoggerInterface|MockObject
     */
    private $logger;

    /**
     * @var MockObject|NormalizerInterface
     */
    private $normalizer;

    public function testConfiguration(): void
    {
        $extension = $this->createExtension(new FactoryRegistry([]), new ReaderRegistry([]));
        $options = $this->resolveExtensionOptions($extension, []);

        $this->assertCount(1, ParsleyTypeExtension::getExtendedTypes());
        $this->assertContains(FormType::class, ParsleyTypeExtension::getExtendedTypes());
        $this->assertTrue($options['parsley_enabled']);
        $this->assertSame('blur', $options['parsley_trigger_event']);
    }

    /**
     * @param bool $enabled
     * @param bool $parsleyEnabled
     *
     * @dataProvider provideFinishViewDisabled
     */
    public function testFinishViewDisabled(bool $enabled, bool $parsleyEnabled): void
    {
        $form = $this->createMock(FormInterface::class);
        $view = $this->createMock(FormView::class);

        $extension = $this->createExtension(new FactoryRegistry([]), new ReaderRegistry([]), $enabled);
        $options = $this->resolveExtensionOptions($extension, ['parsley_enabled' => $parsleyEnabled]);
        $extension->finishView($view, $form, $options);

        $this->assertEmpty($view->vars['attr']);
    }

    /**
     * @return array
     */
    public function provideFinishViewDisabled(): array
    {
        return [
            [false, false],
            [true, false],
        ];
    }

    public function testFinishViewRootForm(): void
    {
        $form = $this->createMock(FormInterface::class);
        $view = $this->createMock(FormView::class);

        $this->setUpForm($form, true);

        $extension = $this->createExtension(new FactoryRegistry([]), new ReaderRegistry([]));
        $options = $this->resolveExtensionOptions($extension);
        $extension->finishView($view, $form, $options);

        $this->assertSame(
            [
                'novalidate' => true,
                'data-parsley-validate' => true,
            ],
            $view->vars['attr']
        );
    }

    public function testFinishViewWithoutReaders(): void
    {
        $form = $this->createMock(FormInterface::class);
        $view = $this->createMock(FormView::class);

        $this->setUpForm($form);

        $extension = $this->createExtension(new FactoryRegistry([]), new ReaderRegistry([]));
        $options = $this->resolveExtensionOptions($extension);
        $extension->finishView($view, $form, $options);

        $this->assertSame(['data-parsley-trigger' => 'blur'], $view->vars['attr']);
    }

    public function testFinishViewWithoutConstraints(): void
    {
        $form = $this->createMock(FormInterface::class);
        $view = $this->createMock(FormView::class);

        $this->setUpForm($form);

        $extension = $this->createExtension(new FactoryRegistry([]), new ReaderRegistry([new MockReader([])]));
        $options = $this->resolveExtensionOptions($extension);
        $extension->finishView($view, $form, $options);

        $this->assertSame(['data-parsley-trigger' => 'blur'], $view->vars['attr']);
    }

    public function testFinishViewWithUnsupportedConstraints(): void
    {
        $unsupportedConstraint = new Assert\Valid();

        $form = $this->createMock(FormInterface::class);
        $view = $this->createMock(FormView::class);

        $this->setUpForm($form);

        $this->logger
            ->expects($this->once())
            ->method('warning')
            ->with($this->isType('string'), ['constraint' => $unsupportedConstraint])
        ;

        $extension = $this->createExtension(
            new FactoryRegistry([]),
            new ReaderRegistry([new MockReader([$unsupportedConstraint])])
        );

        $options = $this->resolveExtensionOptions($extension);
        $extension->finishView($view, $form, $options);

        $this->assertSame(['data-parsley-trigger' => 'blur'], $view->vars['attr']);
    }

    public function testFinishViewWithConstraints(): void
    {
        $form = $this->createMock(FormInterface::class);
        $view = $this->createMock(FormView::class);

        $this->setUpForm($form);

        $translator = $this->createMock(TranslatorInterface::class);
        $translator->method('trans')->willReturn('Invalid.');

        $factory1 = new RequiredFactory();
        $factory1->setTranslator($translator);

        $translator = $this->createMock(TranslatorInterface::class);
        $translator->method('trans')->willReturn('Invalid.');

        $factory2 = new DateFactory('foo');
        $factory2->setTranslator($translator);

        $extension = $this->createExtension(
            new FactoryRegistry([$factory1, $factory2]),
            new ReaderRegistry([new MockReader([new Assert\NotBlank(), new Assert\Date()])])
        );

        $options = $this->resolveExtensionOptions($extension);
        $extension->finishView($view, $form, $options);

        $this->assertSame(
            [
                'data-parsley-trigger' => 'blur',
                'data-parsley-required' => 'true',
                'data-parsley-required-message' => 'Invalid.',
                'data-parsley-pattern' => 'foo',
                'data-parsley-pattern-message' => 'Invalid.',
            ],
            $view->vars['attr']
        );
    }

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->normalizer = $this->createMock(NormalizerInterface::class);
    }

    /**
     * @param MockObject $form
     * @param bool $root
     */
    private function setUpForm(MockObject $form, bool $root = false): void
    {
        $form
            ->expects($this->once())
            ->method('isRoot')
            ->willReturn($root)
        ;
    }

    /**
     * @param AbstractTypeExtension $extension
     * @param array $options
     *
     * @return array
     */
    private function resolveExtensionOptions(AbstractTypeExtension $extension, array $options = []): array
    {
        $resolver = new OptionsResolver();
        $extension->configureOptions($resolver);

        return $resolver->resolve($options);
    }

    /**
     * @param FactoryRegistry $factoryRegistry
     * @param ReaderRegistry $readerRegistry
     * @param bool $enabled
     * @param string $triggerEvent
     *
     * @return ParsleyTypeExtension
     */
    private function createExtension(
        FactoryRegistry $factoryRegistry,
        ReaderRegistry $readerRegistry,
        bool $enabled = true,
        string $triggerEvent = 'blur'
    ): ParsleyTypeExtension {
        return new ParsleyTypeExtension(
            $factoryRegistry,
            $this->logger,
            $this->normalizer,
            $readerRegistry,
            $enabled,
            $triggerEvent
        );
    }
}
