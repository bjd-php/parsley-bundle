<?php

namespace JBen87\ParsleyBundle\Constraint\Constraints;

class Integer extends Type
{
    /**
     * @inheritdoc
     */
    protected function getType(): string
    {
        return static::TYPES['integer'];
    }
}
