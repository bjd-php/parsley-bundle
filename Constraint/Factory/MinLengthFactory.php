<?php

namespace JBen87\ParsleyBundle\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

final class MinLengthFactory implements TranslatableFactoryInterface
{
    use FactoryTrait;

    public function create(SymfonyConstraint $constraint): Constraint
    {
        /** @var Assert\Length $constraint */

        return new ParsleyAssert\MinLength([
            'min' => $constraint->min,
            'message' => $this->transChoice(
                $constraint->minMessage,
                (int) $constraint->min,
                ['{{ limit }}' => $constraint->min]
            ),
        ]);
    }

    public function supports(SymfonyConstraint $constraint): bool
    {
        return $constraint instanceof Assert\Length && null !== $constraint->min && null === $constraint->max;
    }
}
