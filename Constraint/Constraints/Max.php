<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class Max extends Constraint
{
    private int $max;

    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->max = $options['max'];
    }

    protected function getAttribute(): string
    {
        return 'data-parsley-max';
    }

    protected function getValue(): string
    {
        return (string) $this->max;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(['max'])
            ->setAllowedTypes('max', ['int'])
        ;
    }
}
