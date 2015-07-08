<?php

namespace JBen87\ParsleyBundle\Validator\ParsleyConstraints;

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
    protected function getValue()
    {
        return 'true';
    }
}
