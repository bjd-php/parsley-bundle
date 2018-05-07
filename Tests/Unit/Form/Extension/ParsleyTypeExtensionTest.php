<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Form\Extension;

use JBen87\ParsleyBundle\Builder\BuilderInterface;
use JBen87\ParsleyBundle\Form\Extension\ParsleyTypeExtension;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ParsleyTypeExtensionTest extends TestCase
{
    /**
     * @var ObjectProphecy|NormalizerInterface
     */
    private $normalizer;

    /**
     * @var ObjectProphecy|BuilderInterface
     */
    private $builder;

    /**
     * @var ObjectProphecy|ValidatorInterface
     */
    private $validator;

    /**
     * @var ObjectProphecy|FormView
     */
    private $view;

    /**
     * @var ObjectProphecy|FormInterface
     */
    private $form;

    public function testConfiguration(): void
    {
        $resolver = new OptionsResolver();

        $extension = $this->createExtension();
        $extension->configureOptions($resolver);

        $this->assertTrue($resolver->isDefined('parsley_enabled'));
        $this->assertTrue($resolver->isDefined('parsley_trigger_event'));
        $this->assertEquals(FormType::class, $extension->getExtendedType());
    }

    public function testBuildViewDisabled(): void
    {
        $attributes = $this->view->vars['attr'];

        $extension = $this->createExtension();
        $this->buildView($extension, ['parsley_enabled' => false]);

        $this->assertArrayNotHasKey('novalidate', $attributes);
        $this->assertArrayNotHasKey('data-parsley-validate', $attributes);
        $this->assertArrayNotHasKey('data-parsley-trigger', $attributes);
    }

    public function testBuildViewRootForm(): void
    {
        $this->configureFormRoot();
        $this->form->count()->shouldBeCalled()->willReturn(2);

        $extension = $this->createExtension();
        $this->buildView($extension);

        $attributes = $this->view->vars['attr'];

        $this->assertArrayHasKey('novalidate', $attributes);
        $this->assertTrue($attributes['novalidate']);
        $this->assertArrayHasKey('data-parsley-validate', $attributes);
        $this->assertTrue($attributes['data-parsley-validate']);
        $this->assertArrayNotHasKey('data-parsley-trigger', $attributes);
    }

    public function testBuildViewChildFormDefaultTriggerEvent(): void
    {
        $this->configureFormChild();

        $extension = $this->createExtension();
        $this->buildView($extension);

        $attributes = $this->view->vars['attr'];

        $this->assertArrayNotHasKey('novalidate', $attributes);
        $this->assertArrayNotHasKey('data-parsley-validate', $attributes);
        $this->assertArrayHasKey('data-parsley-trigger', $attributes);
        $this->assertSame('blur', $attributes['data-parsley-trigger']);
    }

    public function testBuildViewChildFormCustomTriggerEvent(): void
    {
        $this->configureFormChild();

        // override through configuration
        $extension = $this->createExtension('hover');
        $this->buildView($extension);

        $attributes = $this->view->vars['attr'];

        $this->assertArrayNotHasKey('novalidate', $attributes);
        $this->assertArrayNotHasKey('data-parsley-validate', $attributes);
        $this->assertArrayHasKey('data-parsley-trigger', $attributes);
        $this->assertSame('hover', $attributes['data-parsley-trigger']);

        // override through options
        $extension = $this->createExtension();
        $this->buildView($extension, ['parsley_trigger_event' => 'click']);

        $attributes = $this->view->vars['attr'];

        $this->assertArrayNotHasKey('novalidate', $attributes);
        $this->assertArrayNotHasKey('data-parsley-validate', $attributes);
        $this->assertArrayHasKey('data-parsley-trigger', $attributes);
        $this->assertSame('click', $attributes['data-parsley-trigger']);

        // overriding through options should take over on overriding through configuration
        $extension = $this->createExtension('mousein');
        $this->buildView($extension, ['parsley_trigger_event' => 'mouseout']);

        $attributes = $this->view->vars['attr'];

        $this->assertArrayNotHasKey('novalidate', $attributes);
        $this->assertArrayNotHasKey('data-parsley-validate', $attributes);
        $this->assertArrayHasKey('data-parsley-trigger', $attributes);
        $this->assertSame('mouseout', $attributes['data-parsley-trigger']);
    }

    public function testFinishViewDisabled(): void
    {
        $extension = $this->createExtension();
        $this->finishView($extension, ['parsley_enabled' => false]);

        $this->assertCount(0, $this->view->vars['attr']);
    }

    public function testFinishViewRootForm(): void
    {
        $this->configureFormRoot();

        $extension = $this->createExtension();
        $this->finishView($extension);

        $this->assertCount(0, $this->view->vars['attr']);
    }

    /**
     * todo write test
     */
    public function finishViewChild(): void
    {
        $this->configureFormChild();

        $extension = $this->createExtension();
        $this->finishView($extension);

        $attributes = $this->view->vars['attr'];

        $this->assertArrayHasKey('data-parsley-trigger', $attributes);
    }

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        $this->normalizer = $this->prophesize('Symfony\Component\Serializer\Normalizer\NormalizerInterface');
        $this->builder = $this->prophesize('JBen87\ParsleyBundle\Builder\ConstraintBuilder');

        // handle symfony version <= 2.5
        if (interface_exists('Symfony\Component\Validator\Validator\ValidatorInterface')) {
            $this->validator = $this->prophesize('Symfony\Component\Validator\Validator\ValidatorInterface');
        } else {
            $this->validator = $this->prophesize('Symfony\Component\Validator\ValidatorInterface');
        }

        $this->view = $this->prophesize('Symfony\Component\Form\FormView');
        $this->form = $this->prophesize('Symfony\Component\Form\Form');
    }

    /**
     * @param string $triggerEvent
     * @param bool $global
     *
     * @return ParsleyTypeExtension
     */
    private function createExtension(string $triggerEvent = 'blur', bool $global = true): ParsleyTypeExtension
    {
        return new ParsleyTypeExtension(
            $this->builder->reveal(),
            $this->normalizer->reveal(),
            $this->validator->reveal(),
            $global,
            $triggerEvent
        );
    }

    /**
     * Configure form to behave like the root.
     */
    private function configureFormRoot(): void
    {
        $this->form
            ->getParent()
            ->shouldBeCalled()
            ->willReturn(null)
        ;
    }

    /**
     * Configure form to behave like a child.
     */
    private function configureFormChild(): void
    {
        $this->form
            ->getParent()
            ->shouldBeCalled()
            ->willReturn($this->prophesize('Symfony\Component\Form\Form')->reveal())
        ;
    }

    /**
     * @param AbstractTypeExtension $extension
     * @param array $options
     */
    private function buildView(AbstractTypeExtension $extension, array $options = []): void
    {
        $resolver = new OptionsResolver();
        $extension->configureOptions($resolver);

        $extension->buildView($this->view->reveal(), $this->form->reveal(), $resolver->resolve($options));
    }

    /**
     * @param AbstractTypeExtension $extension
     * @param array $options
     */
    private function finishView(AbstractTypeExtension $extension, array $options = []): void
    {
        $resolver = new OptionsResolver();
        $extension->configureOptions($resolver);

        $extension->finishView($this->view->reveal(), $this->form->reveal(), $resolver->resolve($options));
    }
}
