<?php

namespace JBen87\ParsleyBundle\Factory;

use JBen87\ParsleyBundle\Exception\Validator\ConstraintException;
use JBen87\ParsleyBundle\Validator\Constraint as ParsleyConstraint;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

interface FactoryInterface
{
    /**
     * @param SymfonyConstraint $constraint
     *
     * @return ParsleyConstraint
     * @throws ConstraintException
     */
    public function create(SymfonyConstraint $constraint): ParsleyConstraint;

    /**
     * @param SymfonyConstraint $constraint
     *
     * @return bool
     */
    public function supports(SymfonyConstraint $constraint): bool;
}
