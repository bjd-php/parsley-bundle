<?php

namespace JBen87\ParsleyBundle\Constraint\Constraints;

class LessThanOrEqual extends LessThan
{
    /**
     * @inheritdoc
     */
    protected function getAttribute(): string
    {
        return 'data-parsley-lte';
    }
}
