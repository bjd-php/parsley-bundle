<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

final class LessThanFactory implements TranslatableFactoryInterface
{
    use FactoryTrait;

    public function create(SymfonyConstraint $constraint): Constraint
    {
        /** @var Assert\LessThan $constraint */

        return new ParsleyAssert\LessThan([
            'value' => $constraint->value,
            'message' => $this->trans($constraint->message, ['{{ compared_value }}' => $constraint->value]),
        ]);
    }

    public function supports(SymfonyConstraint $constraint): bool
    {
        return $constraint instanceof Assert\LessThan;
    }
}
