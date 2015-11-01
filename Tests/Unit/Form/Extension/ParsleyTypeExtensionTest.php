<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Form\Extension;

use JBen87\ParsleyBundle\Form\Extension\ParsleyTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class ParsleyTypeExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ParsleyTypeExtension
     */
    private $extension;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->extension = new ParsleyTypeExtension('blur');
    }

    /**
     * @test
     */
    public function configuration()
    {
        $resolver = new OptionsResolver();

        $this->extension->setDefaultOptions($resolver);

        // handle symfony version <= 2.6
        if (method_exists($resolver, 'isKnown')) {
            $this->assertTrue($resolver->isKnown('parsley_trigger_event'));
        } else {
            $this->assertTrue($resolver->isDefined('parsley_trigger_event'));
        }

        $this->assertEquals('form', $this->extension->getExtendedType());
    }

    /**
     * @test
     */
    public function viewWithDefaultTriggerEvent()
    {
        $formView = $this->createFormView();

        $this->extension->buildView($formView, $this->createForm(), []);

        $this->assertEquals('blur', $formView->vars['attr']['data-parsley-trigger']);
    }

    /**
     * @test
     */
    public function viewWithCustomTriggerEvent()
    {
        $formView = $this->createFormView();

        $this->extension->buildView($formView, $this->createForm(), [
            'parsley_trigger_event' => 'click',
        ]);

        $this->assertEquals('click', $formView->vars['attr']['data-parsley-trigger']);
    }

    /**
     * @return FormView
     */
    private function createFormView()
    {
        return $this->prophesize('Symfony\\Component\\Form\\FormView')->reveal();
    }

    /**
     * @return FormInterface
     */
    private function createForm()
    {
        return $this->prophesize('Symfony\\Component\\Form\\Form')->reveal();
    }
}
