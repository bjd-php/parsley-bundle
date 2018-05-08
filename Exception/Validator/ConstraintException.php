<?php

namespace JBen87\ParsleyBundle\Exception\Validator;

class ConstraintException extends \Exception
{
    /**
     * @return ConstraintException
     */
    public static function createUnsupportedException(): ConstraintException
    {
        return new self('Constraint not supported.');
    }
}
