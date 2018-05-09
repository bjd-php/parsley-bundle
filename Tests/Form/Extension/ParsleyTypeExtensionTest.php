<?php

namespace JBen87\ParsleyBundle\Tests\Form\Extension;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use JBen87\ParsleyBundle\Constraint\Factory\ChainFactory;
use JBen87\ParsleyBundle\Constraint\Reader\ReaderInterface;
use JBen87\ParsleyBundle\Constraint\Reader\ReaderRegistry;
use JBen87\ParsleyBundle\Exception\ConstraintException;
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
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class ParsleyTypeExtensionTest extends TestCase
{
    /**
     * @var ChainFactory|MockObject
     */
    private $factory;

    /**
     * @var LoggerInterface|MockObject
     */
    private $logger;

    /**
     * @var MockObject|NormalizerInterface
     */
    private $normalizer;

    /**
     * @var MockObject|ReaderRegistry
     */
    private $readerRegistry;

    public function testConfiguration(): void
    {
        $extension = $this->createExtension();
        $this->assertSame(FormType::class, $extension->getExtendedType());

        $resolver = new OptionsResolver();
        $extension->configureOptions($resolver);
        $options = $resolver->resolve([]);

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

        $extension = $this->createExtension($enabled);
        $this->finishView($extension, $view, $form, ['parsley_enabled' => $parsleyEnabled]);

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

        $extension = $this->createExtension();
        $this->finishView($extension, $view, $form);

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

        $extension = $this->createExtension();
        $this->finishView($extension, $view, $form);

        $this->assertSame(['data-parsley-trigger' => 'blur'], $view->vars['attr']);
    }

    public function testFinishViewWithoutConstraints(): void
    {
        $form = $this->createMock(FormInterface::class);
        $view = $this->createMock(FormView::class);

        $this->setUpForm($form);

        $reader = $this->createMock(ReaderInterface::class);
        $reader
            ->expects($this->once())
            ->method('read')
            ->with($this->isInstanceOf(FormInterface::class))
            ->willReturn([])
        ;

        $this->readerRegistry
            ->expects($this->once())
            ->method('all')
            ->willReturn([$reader])
        ;

        $extension = $this->createExtension();
        $this->finishView($extension, $view, $form);

        $this->assertSame(['data-parsley-trigger' => 'blur'], $view->vars['attr']);
    }

    public function testFinishViewWithUnsupportedConstraints(): void
    {
        $unsupportedConstraint = new Assert\Valid();

        $form = $this->createMock(FormInterface::class);
        $view = $this->createMock(FormView::class);

        $this->setUpForm($form);

        $this->factory
            ->expects($this->once())
            ->method('create')
            ->with($unsupportedConstraint)
            ->willThrowException(ConstraintException::createUnsupportedException())
        ;

        $this->logger
            ->expects($this->once())
            ->method('warning')
            ->with($this->isType('string'), ['constraint' => $unsupportedConstraint])
        ;

        $reader = $this->createMock(ReaderInterface::class);
        $reader
            ->expects($this->once())
            ->method('read')
            ->with($this->isInstanceOf(FormInterface::class))
            ->willReturn([$unsupportedConstraint])
        ;

        $this->readerRegistry
            ->expects($this->once())
            ->method('all')
            ->willReturn([$reader])
        ;

        $extension = $this->createExtension();
        $this->finishView($extension, $view, $form);

        $this->assertSame(['data-parsley-trigger' => 'blur'], $view->vars['attr']);
    }

    public function testFinishViewWithConstraints(): void
    {
        $form = $this->createMock(FormInterface::class);
        $view = $this->createMock(FormView::class);

        $this->setUpForm($form);

        $this->factory
            ->expects($this->exactly(2))
            ->method('create')
            ->with($this->isInstanceOf(SymfonyConstraint::class))
            ->willReturnOnConsecutiveCalls(
                new ParsleyAssert\Required(),
                new ParsleyAssert\Pattern(['pattern' => 'foo'])
            )
        ;

        $reader1 = $this->createMock(ReaderInterface::class);
        $reader1
            ->expects($this->once())
            ->method('read')
            ->with($this->isInstanceOf(FormInterface::class))
            ->willReturn([new Assert\NotBlank()])
        ;

        $reader2 = $this->createMock(ReaderInterface::class);
        $reader2
            ->expects($this->once())
            ->method('read')
            ->with($this->isInstanceOf(FormInterface::class))
            ->willReturn([new Assert\Email()])
        ;

        $this->readerRegistry
            ->expects($this->once())
            ->method('all')
            ->willReturn([$reader1, $reader2])
        ;

        $extension = $this->createExtension();
        $this->finishView($extension, $view, $form);

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
        $this->factory = $this->createMock(ChainFactory::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->normalizer = $this->createMock(NormalizerInterface::class);
        $this->readerRegistry = $this->createMock(ReaderRegistry::class);
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
     * @param MockObject|FormView $view
     * @param FormInterface|MockObject $form
     * @param array $options
     */
    private function finishView(
        AbstractTypeExtension $extension,
        MockObject $view,
        MockObject $form,
        array $options = []
    ): void {
        $resolver = new OptionsResolver();
        $extension->configureOptions($resolver);

        $extension->finishView($view, $form, $resolver->resolve($options));
    }

    /**
     * @param bool $enabled
     * @param string $triggerEvent
     *
     * @return ParsleyTypeExtension
     */
    private function createExtension(bool $enabled = true, string $triggerEvent = 'blur'): ParsleyTypeExtension
    {
        return new ParsleyTypeExtension(
            $this->factory,
            $this->logger,
            $this->normalizer,
            $this->readerRegistry,
            $enabled,
            $triggerEvent
        );
    }
}
