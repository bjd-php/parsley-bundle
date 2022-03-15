<?php

namespace JBen87\ParsleyBundle\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class Pattern extends Constraint
{
    private string $pattern;

    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->pattern = $options['pattern'];
    }

    protected function getAttribute(): string
    {
        return 'data-parsley-pattern';
    }

    protected function getValue(): string
    {
        return $this->pattern;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(['pattern'])
            ->setAllowedTypes('pattern', ['string'])
        ;
    }
}
