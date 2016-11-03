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
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\PropertyMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorInterface as DeprecatedValidatorInterface;

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
     * @var ValidatorInterface|DeprecatedValidatorInterface
     */
    private $validator;

    /**
     * @var bool
     */
    private $global;

    /**
     * @var string
     */
    private $triggerEvent;

    /**
     * @param BuilderInterface $constraintBuilder
     * @param NormalizerInterface $normalizer
     * @param ValidatorInterface|DeprecatedValidatorInterface $validator
     * @param bool $global
     * @param string $triggerEvent
     */
    public function __construct(
        BuilderInterface $constraintBuilder,
        NormalizerInterface $normalizer,
        $validator,
        $global,
        $triggerEvent
    ) {
        $this->constraintBuilder = $constraintBuilder;
        $this->normalizer = $normalizer;
        $this->validator = $validator;
        $this->global = $global;
        $this->triggerEvent = $triggerEvent;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['parsley_enabled' => $this->global]);

        if (method_exists($resolver, 'setDefined')) {
            $resolver->setDefined(['parsley_trigger_event']);
        } else {
            $resolver->setOptional(['parsley_trigger_event']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (false === $options['parsley_enabled']) {
            return;
        }

        // enable parsley validation on root form
        if (null === $form->getParent() && count($form) > 0) {
            $view->vars['attr'] += [
                'novalidate' => true,
                'data-parsley-validate' => true,
            ];

            return;
        }

        // set attributes on form children
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
        if (false === $options['parsley_enabled']) {
            return;
        }

        // do nothing with root form
        if (null === $form->getParent()) {
            return;
        }

        // build constraints and map them as data attributes
        // form's constraints should override entity's constraints.
        $this->constraintBuilder->configure([
            'constraints' => array_merge($this->getEntityConstraints($form), $this->getFormTypeConstraints($form)),
        ]);

        /** @var Constraint[] $constraints */
        $constraints = $this->constraintBuilder->build();

        foreach ($constraints as $constraint) {
            $view->vars['attr'] = array_merge($view->vars['attr'], $constraint->normalize($this->normalizer));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix') ?
            'Symfony\Component\Form\Extension\Core\Type\FormType' :
            'form';
    }

    /**
     * @param FormInterface $form
     *
     * @return array
     */
    private function getEntityConstraints(FormInterface $form)
    {
        $config = $form->getParent()->getConfig();

        if (!$config->hasOption('data_class') || !class_exists($config->getOption('data_class'))) {
            return [];
        }

        $constraints = [];

        /** @var ClassMetadata $metadata */
        $metadata = $this->validator->getMetadataFor($config->getDataClass());

        /** @var PropertyMetadata[] $properties */
        $properties = $metadata->getPropertyMetadata($form->getName());

        foreach ($properties as $property) {
            $constraints = array_merge($constraints, $property->findConstraints($metadata->getDefaultGroup()));
        }

        return $constraints;
    }

    /**
     * @param FormInterface $form
     *
     * @return array
     */
    private function getFormTypeConstraints(FormInterface $form)
    {
        $config = $form->getConfig();

        if (!$config->hasOption('constraints')) {
            return [];
        }

        return $config->getOption('constraints');
    }
}
