<?php

namespace JBen87\ParsleyBundle\Constraint\Constraints;

final class Url extends Type
{
    /**
     * @inheritdoc
     */
    protected function getType(): string
    {
        return static::TYPES['url'];
    }
}
