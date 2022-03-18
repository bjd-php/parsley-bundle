<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Constraint;

use JBen87\ParsleyBundle\Exception\ConstraintException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

abstract class Constraint implements NormalizableInterface
{
    private string $message = 'Invalid.';

    public function __construct(array $options = [])
    {
        $options = $this->configure($options);

        if (array_key_exists('message', $options)) {
            $this->message = $options['message'];
        }
    }

    public function normalize(NormalizerInterface $normalizer, $format = null, array $context = []): array
    {
        return [
            $this->getAttribute() => $this->getValue(),
            sprintf('%s-message', $this->getAttribute()) => $this->message,
        ];
    }

    abstract protected function getAttribute(): string;

    /**
     * @throws ConstraintException
     */
    abstract protected function getValue(): string;

    protected function configureOptions(OptionsResolver $resolver): void
    {
    }

    private function configure(array $options): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefined('message');

        $this->configureOptions($resolver);

        return $resolver->resolve($options);
    }
}
