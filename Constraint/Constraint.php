<?php

namespace JBen87\ParsleyBundle\Constraint;

use JBen87\ParsleyBundle\Exception\ConstraintException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

abstract class Constraint implements NormalizableInterface
{
    /**
     * @var string
     */
    private $message = 'Invalid.';

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $options = $this->configure($options);

        if (array_key_exists('message', $options)) {
            $this->message = $options['message'];
        }
    }

    /**
     * @inheritdoc
     */
    public function normalize(NormalizerInterface $normalizer, $format = null, array $context = []): array
    {
        return [
            $this->getAttribute() => $this->getValue(),
            sprintf('%s-message', $this->getAttribute()) => $this->message,
        ];
    }

    /**
     * @return string
     */
    abstract protected function getAttribute(): string;

    /**
     * @return string
     * @throws ConstraintException
     */
    abstract protected function getValue(): string;

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
    }

    /**
     * @param array $options
     *
     * @return array
     */
    private function configure(array $options): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefined('message');

        $this->configureOptions($resolver);

        return $resolver->resolve($options);
    }
}
