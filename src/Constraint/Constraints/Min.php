<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class Min extends Constraint
{
    private int $min;

    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->min = $options['min'];
    }

    protected function getAttribute(): string
    {
        return 'data-parsley-min';
    }

    protected function getValue(): string
    {
        return (string) $this->min;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(['min'])
            ->setAllowedTypes('min', ['int'])
        ;
    }
}
