<?php

namespace JBen87\ParsleyBundle\Constraint\Constraints;

final class Number extends Type
{
    /**
     * @inheritdoc
     */
    protected function getType(): string
    {
        return static::TYPES['number'];
    }
}
