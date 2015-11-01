<?php

namespace JBen87\ParsleyBundle\Exception\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class UnsupportedConstraintException extends ConstraintException
{
    /**
     * @param Constraint $constraint
     */
    public function __construct(Constraint $constraint)
    {
        parent::__construct(
            sprintf('The Parsley constraint has not been written yet for "%s".', get_class($constraint))
        );
    }
}
