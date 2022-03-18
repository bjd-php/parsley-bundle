<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class MaxLength extends Constraint
{
    private int $max;

    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->max = $options['max'];
    }

    public function normalize(NormalizerInterface $normalizer, $format = null, array $context = []): array
    {
        return parent::normalize($normalizer, $format, $context) + ['maxlength' => $this->getValue()];
    }

    protected function getAttribute(): string
    {
        return 'data-parsley-maxlength';
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
