<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Exception\ConstraintException;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

interface FactoryInterface
{
    /**
     * @throws ConstraintException
     */
    public function create(SymfonyConstraint $constraint): Constraint;

    public function supports(SymfonyConstraint $constraint): bool;
}
