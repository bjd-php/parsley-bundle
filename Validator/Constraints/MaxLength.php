<?php

namespace JBen87\ParsleyBundle\Validator\Constraints;

use JBen87\ParsleyBundle\Validator\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class MaxLength extends Constraint
{
    /**
     * @var int
     */
    private $max;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->max = $options['max'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttribute()
    {
        return 'data-parsley-maxlength';
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
        return $this->max;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('max')
            ->setAllowedTypes('max', 'int');
    }
}
