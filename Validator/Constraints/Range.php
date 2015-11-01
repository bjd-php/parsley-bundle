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
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->remove('message')
            ->setRequired(['min', 'max'])
            ->setDefined(['minMessage', 'maxMessage'])
            ->setAllowedTypes([
                'min' => 'int',
                'max' => 'int',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(NormalizerInterface $normalizer, $format = null, array $context = [])
    {
        return array_merge(
            $this->constraints['min']->normalize(null, 'array'),
            $this->constraints['max']->normalize(null, 'array')
        );
    }

    /**
     * @param array $defaults
     *
     * @return Min
     */
    protected function createMin(array $defaults)
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
    protected function createMax(array $defaults)
    {
        $options = ['max' => $defaults['max']];

        if (isset($defaults['maxMessage'])) {
            $options['message'] = $defaults['maxMessage'];
        }

        return new Max($options);
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
}
