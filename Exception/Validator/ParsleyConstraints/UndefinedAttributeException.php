<?php

namespace JBen87\ParsleyBundle\Exception\Validator\ParsleyConstraints;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class UndefinedAttributeException extends ParsleyConstraintException
{
    public function __construct()
    {
        $this->message = 'The attribute property must be defined.';
    }
}
