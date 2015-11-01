<?php

namespace JBen87\ParsleyBundle\Validator\Constraints;

use JBen87\ParsleyBundle\Validator\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class MinLength extends Constraint
{
    /**
     * @var int
     */
    protected $min;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->min = $options['min'];
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('min')
            ->setAllowedTypes('min', 'int');
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttribute()
    {
        return 'data-parsley-minlength';
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
        return $this->min;
    }
}
