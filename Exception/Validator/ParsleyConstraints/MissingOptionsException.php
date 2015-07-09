<?php

namespace JBen87\ParsleyBundle\Exception\Validator\ParsleyConstraints;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class MissingOptionsException extends ParsleyConstraintException
{
    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $message = 'The option "%s" is required.';

        if (count($options) > 1) {
            $message = 'The options "%s" are required.';
        }

        $this->message = sprintf($message, implode('", "', $options));
    }
}
