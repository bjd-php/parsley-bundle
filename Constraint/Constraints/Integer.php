<?php

namespace JBen87\ParsleyBundle\Constraint\Constraints;

final class Integer extends Type
{
    protected function getType(): string
    {
        return self::TYPE_INTEGER;
    }
}
