<?php

namespace ParsleyBundle\Validator\ParsleyConstraints;

use ParsleyBundle\Exception\Validator\ParsleyConstraints\UndefinedAttributeException;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
abstract class ParsleyConstraint implements ParsleyConstraintInterface
{
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
            $this->getAttribute()                           => $this->getValue(),
            sprintf('%s-message', $this->getAttribute())    => $this->message
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        if (null === $this->renderAttribute() || '' === $this->renderAttribute()) {
            throw new UndefinedAttributeException();
        }

        return sprintf('%s %s', $this->renderAttribute(), $this->renderMessage());
    }

    /**
     * @return string
     */
    abstract protected function getAttribute();

    /**
     * @return string
     */
    abstract protected function getValue();

    /**
     * @return string
     */
    abstract protected function renderAttribute();

    /**
     * @return string
     */
    protected function renderMessage()
    {
        return sprintf('%s-message="%s"', $this->attribute, $this->message);
    }
}
