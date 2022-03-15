<?php

namespace JBen87\ParsleyBundle\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class Length extends Constraint
{
    private int $min;
    private int $max;

    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->min = $options['min'];
        $this->max = $options['max'];
    }

    protected function getAttribute(): string
    {
        return 'data-parsley-length';
    }

    protected function getValue(): string
    {
        return "[$this->min, $this->max]";
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(['min', 'max'])
            ->setAllowedTypes('min', ['int'])
            ->setAllowedTypes('max', ['int'])
        ;
    }
}
