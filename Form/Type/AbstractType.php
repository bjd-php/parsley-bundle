<?php

namespace JBen87\ParsleyBundle\Form\Type;

use JBen87\ParsleyBundle\Builder\ConstraintBuilder;
use JBen87\ParsleyBundle\Validator\Constraint;
use Symfony\Component\Form\AbstractType as BaseType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
abstract class AbstractType extends BaseType
{
    /**
     * @var ConstraintBuilder
     */
    private $constraintBuilder;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param ConstraintBuilder $constraintBuilder
     * @param SerializerInterface $serializer
     */
    public function __construct(ConstraintBuilder $constraintBuilder, SerializerInterface $serializer)
    {
        $this->constraintBuilder = $constraintBuilder;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        // enable parsley validation
        $view->vars['attr'] += [
            'novalidate' => true,
            'data-parsley-validate' => true,
        ];

        // generate parsley constraints for children and map them
        foreach ($form as $child) {
            /** @var FormInterface $child */

            $attributes = $child->getConfig()->getAttribute('data_collector/passed_options');

            if (isset($attributes['constraints'])) {
                $this->constraintBuilder->configure([
                    'constraints' => $attributes['constraints'],
                ]);

                foreach ($this->constraintBuilder->build() as $constraint) {
                    /** @var Constraint $constraint */

                    $view[$child->getName()]->vars['attr'] += $this->serializer->serialize($constraint, 'array');
                }
            }
        }
    }
}
