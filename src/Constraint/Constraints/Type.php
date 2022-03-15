<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Exception\ConstraintException;

abstract class Type extends Constraint
{
    protected const TYPE_EMAIL = 'email';
    protected const TYPE_INTEGER = 'integer';
    protected const TYPE_NUMBER = 'number';
    protected const TYPE_URL = 'url';

    private const TYPES = [self::TYPE_EMAIL, self::TYPE_INTEGER, self::TYPE_NUMBER, self::TYPE_URL];

    protected function getAttribute(): string
    {
        return 'data-parsley-type';
    }

    protected function getValue(): string
    {
        if (!in_array($this->getType(), self::TYPES, true)) {
            throw ConstraintException::createInvalidValueException();
        }

        return $this->getType();
    }

    abstract protected function getType(): string;
}
