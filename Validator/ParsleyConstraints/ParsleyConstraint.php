<?php

namespace JBen87\ParsleyBundle\Validator\ParsleyConstraints;

use JBen87\ParsleyBundle\Exception\Validator\ParsleyConstraints\UndefinedAttributeException;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
abstract class ParsleyConstraint implements ParsleyConstraintInterface
{
    /**
     * @var string
     */
    protected $attribute;

    /**
     * @var string
     */
    protected $message = 'Invalid.';

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (isset($options['message'])) {
            $this->message = $options['message'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            $this->attribute                        => $this->getValue(),
            sprintf('%s-message', $this->attribute) => $this->message
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        if (null === $this->attribute) {
            throw new UndefinedAttributeException();
        }

        return sprintf('%s %s', $this->renderAttribute(), $this->renderMessage());
    }

    /**
     * @return string
     */
    abstract protected function getValue();

    /**
     * @return string
     */
    protected function renderAttribute()
    {
        return sprintf('%s="%s"', $this->attribute, $this->getValue());
    }

    /**
     * @return string
     */
    protected function renderMessage()
    {
        return sprintf('%s-message="%s"', $this->attribute, $this->message);
    }
}
