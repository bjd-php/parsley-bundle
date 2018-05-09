<?php

namespace JBen87\ParsleyBundle\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraint;

class Required extends Constraint
{
    /**
     * @inheritdoc
     */
    protected function getAttribute(): string
    {
        return 'data-parsley-required';
    }

    /**
     * @inheritdoc
     */
    protected function getValue(): string
    {
        return 'true';
    }
}
