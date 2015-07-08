<?php

namespace JBen87\ParsleyBundle\Validator\ParsleyConstraints;

use JBen87\ParsleyBundle\Exception\Validator\ParsleyConstraints\MissingOptionsException;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class Range implements ParsleyConstraintInterface
{
    /**
     * @var ParsleyConstraint[]
     */
    protected $constraints;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (!isset($options['min']) || !isset($options['max'])) {
            throw new MissingOptionsException(['min', 'max']);
        }

        $this->constraints = [
            'min'   => $this->createMin($options),
            'max'   => $this->createMax($options)
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array_merge(
            $this->constraints['min']->toArray(),
            $this->constraints['max']->toArray()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return sprintf('%s %s', $this->constraints['min']->render(), $this->constraints['max']->render());
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
}
