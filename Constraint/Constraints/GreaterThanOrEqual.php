<?php

namespace JBen87\ParsleyBundle\Constraint\Constraints;

class GreaterThanOrEqual extends GreaterThan
{
    /**
     * @inheritdoc
     */
    protected function getAttribute(): string
    {
        return 'data-parsley-gte';
    }
}
