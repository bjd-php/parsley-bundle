<?php

namespace JBen87\ParsleyBundle\Constraint\Constraints;

class Email extends Type
{
    /**
     * @inheritdoc
     */
    protected function getType(): string
    {
        return static::TYPES['email'];
    }
}
