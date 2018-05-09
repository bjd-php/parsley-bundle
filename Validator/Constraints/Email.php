<?php

namespace JBen87\ParsleyBundle\Validator\Constraints;

class Email extends Type
{
    /**
     * @inheritdoc
     */
    protected function getType(): string
    {
        return 'email';
    }
}
