<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

final class NumberFactory implements TranslatableFactoryInterface
{
    use FactoryTrait;

    private const TYPE = 'numeric';

    public function create(SymfonyConstraint $constraint): Constraint
    {
        /** @var Assert\Type $constraint */

        return new ParsleyAssert\Number([
            'message' => $this->trans($constraint->message, ['{{ type }}' => self::TYPE]),
        ]);
    }

    public function supports(SymfonyConstraint $constraint): bool
    {
        return $constraint instanceof Assert\Type && self::TYPE === $constraint->type;
    }
}
