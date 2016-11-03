<?php

namespace JBen87\ParsleyBundle\Validator\Constraints;

use JBen87\ParsleyBundle\Validator\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class Range extends Constraint
{
    /**
     * @var Constraint[]
     */
    private $constraints;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->constraints = [
            'min' => $this->createMin($options),
            'max' => $this->createMax($options),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(NormalizerInterface $normalizer, $format = null, array $context = [])
    {
        return array_merge(
            $this->constraints['min']->normalize($normalizer, 'array'),
            $this->constraints['max']->normalize($normalizer, 'array')
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttribute()
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['min', 'max']);

        if (method_exists($resolver, 'setDefined')) {
            $resolver
                ->setAllowedTypes('min', ['int'])
                ->setAllowedTypes('max', ['int']);
        } else {
            $resolver->setAllowedTypes([
                'min' => 'int',
                'max' => 'int',
            ]);
        }

        // handle symfony version <= 2.5
        if (method_exists($resolver, 'remove')) {
            $resolver->remove('message');
        }

        // handle symfony version <= 2.5
        if (method_exists($resolver, 'setDefined')) {
            $resolver->setDefined(['minMessage', 'maxMessage']);
        } else {
            $resolver->setOptional(['minMessage', 'maxMessage']);
        }
    }

    /**
     * @param array $defaults
     *
     * @return Min
     */
    private function createMin(array $defaults)
    {
        $options = ['min' => $defaults['min']];

        if (isset($defaults['minMessage'])) {
            $options['message'] = $defaults['minMessage'];
        }

        return new Min($options);
    }

    /**
     * @param array $defaults
     *
     * @return Max
     */
    private function createMax(array $defaults)
    {
        $options = ['max' => $defaults['max']];

        if (isset($defaults['maxMessage'])) {
            $options['message'] = $defaults['maxMessage'];
        }

        return new Max($options);
    }
}
