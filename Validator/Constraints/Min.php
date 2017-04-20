<?php

namespace JBen87\ParsleyBundle\Validator\Constraints;

use JBen87\ParsleyBundle\Validator\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class Min extends Constraint
{
    /**
     * @var int
     */
    private $min;

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
    protected function getAttribute()
    {
        return 'data-parsley-min';
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
        return $this->min;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['min']);

        if (method_exists($resolver, 'setDefined')) {
            $resolver->setAllowedTypes('min', ['int', 'string']);
        } else {
            $resolver->setAllowedTypes([
                'min' => 'int',
            ]);
        }
    }
}
