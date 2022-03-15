<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

final class IntegerFactory implements TranslatableFactoryInterface
{
    use FactoryTrait;

    private const TYPE = 'integer';
    private const TYPE_ALT = 'int';

    public function create(SymfonyConstraint $constraint): Constraint
    {
        /** @var Assert\Type $constraint */

        return new ParsleyAssert\Integer([
            'message' => $this->trans($constraint->message, ['{{ type }}' => self::TYPE]),
        ]);
    }

    public function supports(SymfonyConstraint $constraint): bool
    {
        return $constraint instanceof Assert\Type
            && (self::TYPE === $constraint->type || self::TYPE_ALT === $constraint->type)
        ;
    }
}
