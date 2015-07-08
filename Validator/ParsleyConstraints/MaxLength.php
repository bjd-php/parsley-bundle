<?php

namespace JBen87\ParsleyBundle\Validator\ParsleyConstraints;

use JBen87\ParsleyBundle\Exception\Validator\ParsleyConstraints\MissingOptionsException;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class MaxLength extends ParsleyConstraint
{
    /**
     * @var string
     */
    protected $attribute = 'data-parsley-maxlength';

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

        if (!isset($options['max'])) {
            throw new MissingOptionsException(['max']);
        }

        $this->max = $options['max'];
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
        return $this->max;
    }

    /**
     * {@inheritdoc}
     */
    protected function renderAttribute()
    {
        return sprintf('%s="%d"', $this->attribute, $this->max);
    }
}
