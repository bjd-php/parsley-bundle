<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

final class EmailFactory implements TranslatableFactoryInterface
{
    use FactoryTrait;

    public function create(SymfonyConstraint $constraint): Constraint
    {
        /** @var Assert\Email $constraint */

        return new ParsleyAssert\Email([
            'message' => $this->trans($constraint->message),
        ]);
    }

    public function supports(SymfonyConstraint $constraint): bool
    {
        return $constraint instanceof Assert\Email;
    }
}
