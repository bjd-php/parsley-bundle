<?php

namespace JBen87\ParsleyBundle\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Exception\ConstraintException;

abstract class Type extends Constraint
{
    const TYPES = [
        'alphanum' => 'alphanum',
        'digits' => 'digits',
        'email' => 'email',
        'integer' => 'integer',
        'number' => 'number',
        'url' => 'url',
    ];

    /**
     * @inheritdoc
     */
    protected function getAttribute(): string
    {
        return 'data-parsley-type';
    }

    /**
     * @inheritdoc
     */
    protected function getValue(): string
    {
        if (false === in_array($this->getType(), static::TYPES)) {
            throw ConstraintException::createInvalidValueException();
        }

        return $this->getType();
    }

    /**
     * @return string
     */
    abstract protected function getType(): string;
}
