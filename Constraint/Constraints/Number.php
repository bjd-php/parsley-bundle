<?php

namespace JBen87\ParsleyBundle\Constraint\Constraints;

final class Number extends Type
{
    protected function getType(): string
    {
        return self::TYPE_NUMBER;
    }
}
