<?php

namespace JBen87\ParsleyBundle\Validator;

use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Exception\UnsupportedException;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
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

        if (isset($options['message'])) {
            $this->message = $options['message'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(NormalizerInterface $normalizer, $format = null, array $context = [])
    {
        if ('array' !== $format) {
            throw new UnsupportedException();
        }

        return [
            $this->getAttribute() => $this->getValue(),
            sprintf('%s-message', $this->getAttribute()) => $this->message,
        ];
    }

    /**
     * @return string
     */
    abstract protected function getAttribute();

    /**
     * @return string
     */
    abstract protected function getValue();

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * @param array $options
     *
     * @return array
     *
     * @throws MissingOptionsException
     */
    private function configure(array $options)
    {
        $resolver = new OptionsResolver();

        // handle symfony version <= 2.5
        if (method_exists($resolver, 'setDefined')) {
            $resolver->setDefined('message');
        } else {
            $resolver->setOptional(['message']);
        }

        $this->configureOptions($resolver);

        return $resolver->resolve($options);
    }
}
