<?php

namespace ParsleyBundle\Validator\ParsleyConstraints;

use ParsleyBundle\Exception\Validator\ParsleyConstraints\MissingOptionsException;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class MinLength extends ParsleyConstraint
{
    /**
     * @var string
     */
    protected $attribute = 'data-parsley-minlength';

    /**
     * @var int
     */
    protected $min;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        if (!isset($options['min'])) {
            throw new MissingOptionsException(['min']);
        }

        $this->min = $options['min'];
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
        return $this->min;
    }

    /**
     * {@inheritdoc}
     */
    protected function renderAttribute()
    {
        return sprintf('%s="%d"', $this->attribute, $this->min);
    }
}
