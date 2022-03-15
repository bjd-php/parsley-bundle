<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Tests\Form\Extension;

use JBen87\ParsleyBundle\Constraint\Factory\DateFactory;
use JBen87\ParsleyBundle\Constraint\Factory\FactoryRegistry;
use JBen87\ParsleyBundle\Constraint\Factory\RequiredFactory;
use JBen87\ParsleyBundle\Constraint\Reader\ReaderInterface;
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
     * @var MockObject|LoggerInterface|null
     */
    private ?MockObject $logger = null;

    /**
     * @var MockObject|NormalizerInterface|null
     */
    private ?MockObject $normalizer = null;

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

        $extension = $this->createExtension(
            new FactoryRegistry([]),
            new ReaderRegistry([
                new class implements ReaderInterface
                {
                    public function read(FormInterface $form): array
                    {
                        return [];
                    }

                    public function getPriority(): int
                    {
                        return 0;
                    }
                },
            ])
        );

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
            new ReaderRegistry([
                new class([$unsupportedConstraint]) implements ReaderInterface
                {
                    private array $data;

                    public function __construct(array $data)
                    {
                        $this->data = $data;
                    }

                    public function read(FormInterface $form): array
                    {
                        return $this->data;
                    }

                    public function getPriority(): int
                    {
                        return 0;
                    }
                },
            ])
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
            new ReaderRegistry([
                new class([new Assert\NotBlank(), new Assert\Date()]) implements ReaderInterface
                {
                    private array $data;

                    public function __construct(array $data)
                    {
                        $this->data = $data;
                    }

                    public function read(FormInterface $form): array
                    {
                        return $this->data;
                    }

                    public function getPriority(): int
                    {
                        return 0;
                    }
                },
            ])
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

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->normalizer = $this->createMock(NormalizerInterface::class);
    }

    /**
     * @param MockObject|FormInterface $form
     */
    private function setUpForm(MockObject $form, bool $root = false): void
    {
        $form
            ->expects($this->once())
            ->method('isRoot')
            ->willReturn($root)
        ;
    }

    private function resolveExtensionOptions(AbstractTypeExtension $extension, array $options = []): array
    {
        $resolver = new OptionsResolver();
        $extension->configureOptions($resolver);

        return $resolver->resolve($options);
    }

    private function createExtension(
        FactoryRegistry $factoryRegistry,
        ReaderRegistry $readerRegistry,
        bool $enabled = true
    ): ParsleyTypeExtension {
        return new ParsleyTypeExtension(
            $factoryRegistry,
            $this->logger,
            $this->normalizer,
            $readerRegistry,
            $enabled,
            'blur'
        );
    }
}
