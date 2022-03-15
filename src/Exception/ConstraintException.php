<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Exception;

final class ConstraintException extends \Exception
{
    public static function createUnsupportedException(): ConstraintException
    {
        return new self('Constraint not supported.');
    }

    public static function createInvalidValueException(): ConstraintException
    {
        return new self('Invalid value.');
    }
}
