<?php

namespace JBen87\ParsleyBundle\Validator\Constraints;

use JBen87\ParsleyBundle\Validator\Constraint;

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
