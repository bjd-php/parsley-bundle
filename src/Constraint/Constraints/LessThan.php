<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class LessThan extends Constraint
{
    /**
     * @var int|float|string
     */
    private $value;

    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->value = $options['value'];
    }

    protected function getAttribute(): string
    {
        return 'data-parsley-lt';
    }

    protected function getValue(): string
    {
        return (string) $this->value;
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(['value'])
            ->setAllowedTypes('value', ['numeric'])
        ;
    }
}
