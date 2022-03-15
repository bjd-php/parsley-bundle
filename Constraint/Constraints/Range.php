<?php

namespace JBen87\ParsleyBundle\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class Range extends Constraint
{
    /**
     * @var Constraint[]
     */
    private array $constraints;

    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->constraints = [
            'min' => $this->createMin($options),
            'max' => $this->createMax($options),
        ];
    }

    public function normalize(NormalizerInterface $normalizer, $format = null, array $context = []): array
    {
        return array_merge(
            $this->constraints['min']->normalize($normalizer, 'array'),
            $this->constraints['max']->normalize($normalizer, 'array')
        );
    }

    /**
     * @codeCoverageIgnore
     */
    protected function getAttribute(): string
    {
        throw new \RuntimeException('Should not be called.');
    }

    /**
     * @codeCoverageIgnore
     */
    protected function getValue(): string
    {
        throw new \RuntimeException('Should not be called.');
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(['min', 'max'])
            ->setAllowedTypes('min', ['numeric'])
            ->setAllowedTypes('max', ['numeric'])
            ->setDefined(['minMessage', 'maxMessage'])
        ;
    }

    private function createMin(array $defaults): Min
    {
        $options = ['min' => $defaults['min']];

        if (array_key_exists('minMessage', $defaults)) {
            $options['message'] = $defaults['minMessage'];
        }

        return new Min($options);
    }

    private function createMax(array $defaults): Max
    {
        $options = ['max' => $defaults['max']];

        if (array_key_exists('maxMessage', $defaults)) {
            $options['message'] = $defaults['maxMessage'];
        }

        return new Max($options);
    }
}
