<?php

namespace JBen87\ParsleyBundle\Tests\Form\Extension;

use JBen87\ParsleyBundle\Exception\Validator\ConstraintException;
use JBen87\ParsleyBundle\Factory\ChainFactory;
use JBen87\ParsleyBundle\Form\Extension\ParsleyTypeExtension;
use JBen87\ParsleyBundle\Validator\Constraints as ParsleyAssert;
use JBen87\ParsleyBundle\Validator\ConstraintsReader\ConstraintsReaderInterface;
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
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
     * @var MockObject|ValidatorInterface
     */
    private $validator;

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

        $extension = $this->createExtension([], $enabled);
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

        $reader = $this->createMock(ConstraintsReaderInterface::class);
        $reader
            ->expects($this->once())
            ->method('read')
            ->with($this->isInstanceOf(FormInterface::class))
            ->willReturn([])
        ;

        $extension = $this->createExtension([$reader]);
        $this->finishView($extension, $view, $form);

        $this->assertSame(['data-parsley-trigger' => 'blur'], $view->vars['attr']);
    }

    public function testFinishViewWithUnsupportedConstraints(): void
    {
        $unsupportedConstraint = new Assert\Valid();

        $form = $this->createMock(FormInterface::class);
        $view = $this->createMock(FormView::class);

        $this->setUpForm($form);

        $reader = $this->createMock(ConstraintsReaderInterface::class);
        $reader
            ->expects($this->once())
            ->method('read')
            ->with($this->isInstanceOf(FormInterface::class))
            ->willReturn([$unsupportedConstraint])
        ;

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

        $extension = $this->createExtension([$reader]);
        $this->finishView($extension, $view, $form);

        $this->assertSame(['data-parsley-trigger' => 'blur'], $view->vars['attr']);
    }

    public function testFinishViewWithConstraints(): void
    {
        $form = $this->createMock(FormInterface::class);
        $view = $this->createMock(FormView::class);

        $this->setUpForm($form);

        $reader1 = $this->createMock(ConstraintsReaderInterface::class);
        $reader1
            ->expects($this->once())
            ->method('read')
            ->with($this->isInstanceOf(FormInterface::class))
            ->willReturn([new Assert\NotBlank()])
        ;

        $reader2 = $this->createMock(ConstraintsReaderInterface::class);
        $reader2
            ->expects($this->once())
            ->method('read')
            ->with($this->isInstanceOf(FormInterface::class))
            ->willReturn([new Assert\Email()])
        ;

        $this->factory
            ->expects($this->exactly(2))
            ->method('create')
            ->with($this->isInstanceOf(SymfonyConstraint::class))
            ->willReturnOnConsecutiveCalls(
                new ParsleyAssert\Required(),
                new ParsleyAssert\Pattern(['pattern' => 'foo'])
            )
        ;

        $extension = $this->createExtension([$reader1, $reader2]);
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
        $this->validator = $this->createMock(ValidatorInterface::class);
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
     * @param ConstraintsReaderInterface[] $readers
     * @param bool $enabled
     * @param string $triggerEvent
     *
     * @return ParsleyTypeExtension
     */
    private function createExtension(
        array $readers = [],
        bool $enabled = true,
        string $triggerEvent = 'blur'
    ): ParsleyTypeExtension {
        return new ParsleyTypeExtension(
            $this->factory,
            $this->logger,
            $this->normalizer,
            $readers,
            $enabled,
            $triggerEvent
        );
    }
}
