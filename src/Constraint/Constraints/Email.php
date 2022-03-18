<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Constraint\Constraints;

final class Email extends Type
{
    protected function getType(): string
    {
        return self::TYPE_EMAIL;
    }
}
