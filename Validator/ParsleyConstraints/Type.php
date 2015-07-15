<?php

namespace JBen87\ParsleyBundle\Validator\ParsleyConstraints;

use JBen87\ParsleyBundle\Exception\Validator\ParsleyConstraints\MissingOptionsException;
use JBen87\ParsleyBundle\Validator\ParsleyConstraint;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class Type extends ParsleyConstraint
{
    /**
     * @var string
     */
    protected $attribute = 'data-parsley-type';

    /**
     * @var string
     */
    protected $type;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        if (!isset($options['type'])) {
            throw new MissingOptionsException(['type']);
        }

        $this->type = $options['type'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
        return $this->type;
    }
}
