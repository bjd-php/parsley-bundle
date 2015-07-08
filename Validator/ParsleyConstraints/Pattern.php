<?php

namespace JBen87\ParsleyBundle\Validator\ParsleyConstraints;

use JBen87\ParsleyBundle\Exception\Validator\ParsleyConstraints\MissingOptionsException;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class Pattern extends ParsleyConstraint
{
    /**
     * @var string
     */
    protected $attribute = 'data-parsley-pattern';

    /**
     * @var string
     */
    protected $pattern;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        if (!isset($options['pattern'])) {
            throw new MissingOptionsException(['pattern']);
        }

        $this->pattern = $options['pattern'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
        return $this->pattern;
    }

    /**
     * {@inheritdoc}
     */
    protected function renderAttribute()
    {
        return sprintf('%s="%s"', $this->attribute, $this->pattern);
    }
}
