<?php

namespace JBen87\ParsleyBundle\Validator\Constraints;

use JBen87\ParsleyBundle\Validator\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Min extends Constraint
{
    /**
     * @var int
     */
    private $min;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->min = $options['min'];
    }

    /**
     * @inheritdoc
     */
    protected function getAttribute(): string
    {
        return 'data-parsley-min';
    }

    /**
     * @inheritdoc
     */
    protected function getValue(): string
    {
        return (string) $this->min;
    }

    /**
     * @inheritdoc
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(['min'])
            ->setAllowedTypes('min', ['int'])
        ;
    }
}
