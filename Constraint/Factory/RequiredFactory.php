<?php

namespace JBen87\ParsleyBundle\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

final class RequiredFactory implements TranslatableFactoryInterface
{
    use FactoryTrait;

    public function create(SymfonyConstraint $constraint): Constraint
    {
        /** @var Assert\NotBlank $constraint */

        return new ParsleyAssert\Required([
            'message' => $this->trans($constraint->message),
        ]);
    }

    public function supports(SymfonyConstraint $constraint): bool
    {
        return $constraint instanceof Assert\NotBlank;
    }
}
