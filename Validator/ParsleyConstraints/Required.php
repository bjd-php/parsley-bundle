<?php

namespace ParsleyBundle\Validator\ParsleyConstraints;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class Required extends ParsleyConstraint
{
    /**
     * @var string
     */
    protected $attribute = 'data-parsley-required';

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
        return 'true';
    }

    /**
     * {@inheritdoc}
     */
    protected function renderAttribute()
    {
        return sprintf('%s="true"', $this->attribute);
    }
}
