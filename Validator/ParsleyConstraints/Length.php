<?php

namespace JBen87\ParsleyBundle\Validator\ParsleyConstraints;

use JBen87\ParsleyBundle\Exception\Validator\ParsleyConstraints\MissingOptionsException;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class Length extends ParsleyConstraint
{
    /**
     * @var string
     */
    protected $attribute = 'data-parsley-length';

    /**
     * @var int
     */
    protected $min;

    /**
     * @var int
     */
    protected $max;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        if (!isset($options['min']) || !isset($options['max'])) {
            throw new MissingOptionsException(['min', 'max']);
        }

        $this->min  = $options['min'];
        $this->max  = $options['max'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
        return sprintf('[%d, %d]', $this->min, $this->max);
    }

    /**
     * {@inheritdoc}
     */
    protected function renderAttribute()
    {
        return sprintf('%s="[%d, %d]"', $this->attribute, $this->min, $this->max);
    }
}
