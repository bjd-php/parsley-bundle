<?php

namespace JBen87\ParsleyBundle\Validator\Constraints;

use JBen87\ParsleyBundle\Validator\Constraint;

abstract class Type extends Constraint
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @inheritdoc
     */
    protected function getAttribute(): string
    {
        return 'data-parsley-type';
    }

    /**
     * @inheritdoc
     */
    protected function getValue(): string
    {
        return $this->getType();
    }

    /**
     * @return string
     */
    abstract protected function getType(): string;
}
