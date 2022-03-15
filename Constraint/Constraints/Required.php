<?php

namespace JBen87\ParsleyBundle\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraint;

final class Required extends Constraint
{
    protected function getAttribute(): string
    {
        return 'data-parsley-required';
    }

    protected function getValue(): string
    {
        return 'true';
    }
}
