<?php

namespace JBen87\ParsleyBundle\Exception;

class ConstraintException extends \Exception
{
    /**
     * @return ConstraintException
     */
    public static function createUnsupportedException(): ConstraintException
    {
        return new self('Constraint not supported.');
    }

    /**
     * @return ConstraintException
     */
    public static function createInvalidValueException(): ConstraintException
    {
        return new self('Invalid value.');
    }
}
