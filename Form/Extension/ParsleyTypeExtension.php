<?php

namespace JBen87\ParsleyBundle\Form\Extension;

use JBen87\ParsleyBundle\Builder\BuilderInterface;
use JBen87\ParsleyBundle\Validator\Constraint;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class ParsleyTypeExtension extends AbstractTypeExtension
{
    /**
     * @var BuilderInterface
     */
    private $constraintBuilder;

    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * @var string
     */
    private $triggerEvent;

    /**
     * @param BuilderInterface $constraintBuilder
     * @param NormalizerInterface $normalizer
     * @param string $triggerEvent
     */
    public function __construct(BuilderInterface $constraintBuilder, NormalizerInterface $normalizer, $triggerEvent)
    {
        $this->constraintBuilder = $constraintBuilder;
        $this->normalizer = $normalizer;
        $this->triggerEvent = $triggerEvent;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $this->setDefaultOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['parsley_enabled' => true]);

        if (method_exists($resolver, 'setDefined')) {
            $resolver->setDefined(['parsley_trigger_event']);
        } else {
            $resolver->setOptional(['parsley_trigger_event']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (!$options['parsley_enabled']) {
            return;
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
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if (!$options['parsley_enabled']) {
            return;
        }

        // enable parsley validation
        $view->vars['attr'] += [
            'novalidate' => true,
            'data-parsley-validate' => true,
        ];

        // generate parsley constraints for children and map them as attributes
        foreach ($form as $child) {
            /** @var FormInterface $child */

            $attributes = $child->getConfig()->getAttribute('data_collector/passed_options');

            if (isset($attributes['constraints'])) {
                $this->constraintBuilder->configure([
                    'constraints' => $attributes['constraints'],
                ]);

                foreach ($this->constraintBuilder->build() as $constraint) {
                    /** @var Constraint $constraint */
                    $view[$child->getName()]->vars['attr'] += $constraint->normalize($this->normalizer);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'form';
    }
}
