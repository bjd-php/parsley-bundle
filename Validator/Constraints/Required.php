<?php

namespace JBen87\ParsleyBundle\Validator\Constraints;

use JBen87\ParsleyBundle\Validator\Constraint;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class Required extends Constraint
{
    /**
     * {@inheritdoc}
     */
    protected function getAttribute()
    {
        return 'data-parsley-required';
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
        return 'true';
    }
}
