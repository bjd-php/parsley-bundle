<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Form\Extension;

use JBen87\ParsleyBundle\Builder\ConstraintBuilder;
use JBen87\ParsleyBundle\Form\Extension\ParsleyTypeExtension;
use Prophecy\Prophecy\ObjectProphecy;
use SplStack;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class ParsleyTypeExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectProphecy|ObjectNormalizer
     */
    private $normalizer;

    /**
     * @var ObjectProphecy|ConstraintBuilder
     */
    private $builder;

    /**
     * @var ObjectProphecy|FormView
     */
    private $view;

    /**
     * @var ObjectProphecy|Form
     */
    private $form;

    /**
     * @test
     */
    public function configuration()
    {
        $resolver = new OptionsResolver();

        $extension = $this->createExtension();
        $extension->setDefaultOptions($resolver);

        $this->assertTrue($resolver->hasDefault('parsley_enabled'));

        // handle symfony version <= 2.6
        if (method_exists($resolver, 'isDefined')) {
            $this->assertTrue($resolver->isDefined('parsley_enabled'));
            $this->assertTrue($resolver->isDefined('parsley_trigger_event'));
        } else {
            $this->assertTrue($resolver->isKnown('parsley_enabled'));
            $this->assertTrue($resolver->isKnown('parsley_trigger_event'));
        }

        $this->assertEquals('form', $extension->getExtendedType());
    }

    /**
     * @test
     */
    public function buildViewDisabled()
    {
        $extension = $this->createExtension();
        $this->buildView($extension, ['parsley_enabled' => false]);

        $this->assertArrayNotHasKey('data-parsley-trigger', $this->view->vars['attr']);
    }

    /**
     * @test
     */
    public function buildViewDefault()
    {
        $extension = $this->createExtension();
        $this->buildView($extension);

        $this->assertArrayHasKey('data-parsley-trigger', $this->view->vars['attr']);
        $this->assertSame('blur', $this->view->vars['attr']['data-parsley-trigger']);
    }

    /**
     * @test
     */
    public function buildViewCustomTriggerEvent()
    {
        $extension = $this->createExtension();
        $this->buildView($extension, ['parsley_trigger_event' => 'click']);

        $this->assertArrayHasKey('data-parsley-trigger', $this->view->vars['attr']);
        $this->assertSame('click', $this->view->vars['attr']['data-parsley-trigger']);

        $extension = $this->createExtension('hover');
        $this->buildView($extension);

        $this->assertArrayHasKey('data-parsley-trigger', $this->view->vars['attr']);
        $this->assertSame('hover', $this->view->vars['attr']['data-parsley-trigger']);

        $extension = $this->createExtension('mousein');
        $this->buildView($extension, ['parsley_trigger_event' => 'mouseout']);

        $this->assertArrayHasKey('data-parsley-trigger', $this->view->vars['attr']);
        $this->assertSame('mouseout', $this->view->vars['attr']['data-parsley-trigger']);
    }

    /**
     * @test
     */
    public function finishViewDisabled()
    {
        $extension = $this->createExtension();
        $this->finishView($extension, ['parsley_enabled' => false]);

        $this->assertArrayNotHasKey('novalidate', $this->view->vars['attr']);
        $this->assertArrayNotHasKey('data-parsley-validate', $this->view->vars['attr']);
    }

    /**
     * @test
     */
    public function finishViewDefault()
    {
        $this->form->getIterator()->shouldBeCalled()->willReturn(new SplStack());

        $extension = $this->createExtension();
        $this->finishView($extension);

        $this->assertArrayHasKey('novalidate', $this->view->vars['attr']);
        $this->assertTrue($this->view->vars['attr']['novalidate']);
        $this->assertArrayHasKey('data-parsley-validate', $this->view->vars['attr']);
        $this->assertTrue($this->view->vars['attr']['data-parsley-validate']);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->builder = $this->prophesize('JBen87\ParsleyBundle\Builder\ConstraintBuilder');
        $this->normalizer = $this->prophesize('Symfony\Component\Serializer\Normalizer\ObjectNormalizer');
        $this->view = $this->prophesize('Symfony\Component\Form\FormView');
        $this->form = $this->prophesize('Symfony\Component\Form\Form');
    }

    /**
     * @param string $triggerEvent
     *
     * @return ParsleyTypeExtension
     */
    private function createExtension($triggerEvent = 'blur')
    {
        return new ParsleyTypeExtension($this->builder->reveal(), $this->normalizer->reveal(), $triggerEvent);
    }

    /**
     * @param AbstractTypeExtension $extension
     * @param array $options
     */
    private function buildView(AbstractTypeExtension $extension, array $options = [])
    {
        $resolver = new OptionsResolver();
        $extension->configureOptions($resolver);

        $extension->buildView($this->view->reveal(), $this->form->reveal(), $resolver->resolve($options));
    }

    /**
     * @param AbstractTypeExtension $extension
     * @param array $options
     */
    private function finishView(AbstractTypeExtension $extension, array $options = [])
    {
        $resolver = new OptionsResolver();
        $extension->configureOptions($resolver);

        $extension->finishView($this->view->reveal(), $this->form->reveal(), $resolver->resolve($options));
    }
}
