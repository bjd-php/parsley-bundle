<?php

namespace JBen87\ParsleyBundle\Constraint\Constraints;

final class Integer extends Type
{
    /**
     * @inheritdoc
     */
    protected function getType(): string
    {
        return static::TYPES['integer'];
    }
}
