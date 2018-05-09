<?php

namespace JBen87\ParsleyBundle\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Exception\ConstraintException;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

interface FactoryInterface
{
    /**
     * @param SymfonyConstraint $constraint
     *
     * @return Constraint
     * @throws ConstraintException
     */
    public function create(SymfonyConstraint $constraint): Constraint;

    /**
     * @param SymfonyConstraint $constraint
     *
     * @return bool
     */
    public function supports(SymfonyConstraint $constraint): bool;
}
