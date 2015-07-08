<?php

namespace JBen87\ParsleyBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class ParsleyConstraintsTypeExtension extends AbstractTypeExtension
{
    /**
     * @var string
     */
    protected $triggerEvent;

    /**
     * @param string $triggerEvent
     */
    public function __construct($triggerEvent)
    {
        $this->triggerEvent = $triggerEvent;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(['parsley_constraints']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (!isset($options['parsley_constraints'])) {
            return;
        }

        $constraints = $options['parsley_constraints'];

        foreach ($constraints as $constraint) {
            $view->vars['attr'] = array_merge($view->vars['attr'], $constraint->toArray());
        }

        $triggerEvent = $this->triggerEvent;

        if (isset($options['parsley_trigger_event'])) {
            $triggerEvent = $options['parsley_trigger_event'];
        }

        $view->vars['attr']['data-parsley-trigger'] = $triggerEvent;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'form';
    }
}
