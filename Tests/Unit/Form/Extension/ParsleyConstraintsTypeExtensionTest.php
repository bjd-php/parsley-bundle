<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Form\Extension;

use JBen87\ParsleyBundle\Form\Extension\ParsleyConstraintsTypeExtension;
use JBen87\ParsleyBundle\Validator\ParsleyConstraints;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class ParsleyConstraintsTypeExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function viewWithConstraints()
    {
        $formView   = new FormView();
        $extension  = new ParsleyConstraintsTypeExtension('click');

        $extension->buildView($formView, $this->createForm(), [
            'parsley_constraints' => [
                new ParsleyConstraints\Required(),
                new ParsleyConstraints\Type(['type' => 'email'])
            ]
        ]);

        $this->assertEquals([
            'data-parsley-required'         => 'true',
            'data-parsley-required-message' => 'Invalid.',
            'data-parsley-type'             => 'email',
            'data-parsley-type-message'     => 'Invalid.',
            'data-parsley-trigger'          => 'click'
        ], $formView->vars['attr']);
    }

    /**
     * @test
     */
    public function viewWithoutConstraints()
    {
        $formView   = new FormView();
        $extension  = new ParsleyConstraintsTypeExtension('click');

        $extension->buildView($formView, $this->createForm(), []);

        $this->assertFalse(array_key_exists('data-parsley-trigger', $formView->vars['attr']));
    }

    /**
     * @test
     */
    public function viewWithCustomTriggerEvent()
    {
        $formView   = new FormView();
        $extension  = new ParsleyConstraintsTypeExtension('click');

        $extension->buildView($formView, $this->createForm(), [
            'parsley_constraints' => [],
            'parsley_trigger_event' => 'blur'
        ]);

        $this->assertEquals('blur', $formView->vars['attr']['data-parsley-trigger']);
    }

    /**
     * @test
     */
    public function configuration()
    {
        $resolver   = new OptionsResolver();
        $extension  = new ParsleyConstraintsTypeExtension('click');

        $extension->setDefaultOptions($resolver);

        $this->assertContains('parsley_constraints', $resolver->getDefinedOptions());
        $this->assertEquals('form', $extension->getExtendedType());
    }

    /**
     * @return FormInterface
     */
    protected function createForm()
    {
        return $this->prophesize('Symfony\\Component\\Form\\Form')->reveal();
    }
}
