<?php

namespace JBen87\ParsleyBundle\Form\Type;

use JBen87\ParsleyBundle\Form\Adapter\ConstraintsAdapter;
use JBen87\ParsleyBundle\Validator\ParsleyConstraint;
use Symfony\Component\Form\AbstractType as BaseType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
abstract class AbstractType extends BaseType
{
    /**
     * @var ConstraintsAdapter
     */
    private $constraintsAdapter;

    /**
     * @param ConstraintsAdapter $constraintsAdapter
     */
    public function __construct(ConstraintsAdapter $constraintsAdapter)
    {
        $this->constraintsAdapter = $constraintsAdapter;
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
                $constraints = $this->constraintsAdapter->generateConstraints($attributes['constraints']);
                $currentView = $view->offsetGet($child->getName());

                foreach ($constraints as $constraint) {
                    /** @var ParsleyConstraint $constraint */

                    $currentView->vars['attr'] += $constraint->toArray();
                }
            }
        }
    }
}
