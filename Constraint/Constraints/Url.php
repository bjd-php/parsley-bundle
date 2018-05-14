<?php

namespace JBen87\ParsleyBundle\Constraint\Constraints;

class Url extends Type
{
    /**
     * @inheritdoc
     */
    protected function getType(): string
    {
        return static::TYPES['url'];
    }
}
