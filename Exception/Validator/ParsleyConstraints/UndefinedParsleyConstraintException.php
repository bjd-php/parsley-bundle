<?php

namespace ParsleyBundle\Exception\Validator\ParsleyConstraints;

use Symfony\Component\Validator\Constraint;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class UndefinedParsleyConstraintException extends ParsleyConstraintException
{
    /**
     * @param Constraint $constraint
     */
    public function __construct(Constraint $constraint)
    {
        $this->message = sprintf('The Parsley constraint has not been written yet for "%s".', get_class($constraint));
    }
}
