<?php

namespace JBen87\ParsleyBundle\Validator\Constraints;

use JBen87\ParsleyBundle\Validator\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class Type extends Constraint
{
    /**
     * @var string
     */
    protected $type;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->type = $options['type'];
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('type')
            ->setAllowedTypes('type', 'string')
            ->setAllowedValues('type', ['email', 'number', 'integer', 'digits', 'alphanum', 'url']);
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttribute()
    {
        return 'data-parsley-type';
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
        return $this->type;
    }
}
