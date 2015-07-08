<?php

namespace ParsleyBundle\Exception\Validator\ParsleyConstraints;

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
        $this->message = sprintf('The options "%s" are required.', implode('", "', $options));
    }
}
