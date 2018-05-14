<?php

namespace JBen87\ParsleyBundle\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class NumberFactory implements TranslatableFactoryInterface
{
    use FactoryTrait;

    const TYPE = 'numeric';

    /**
     * @inheritdoc
     */
    public function create(SymfonyConstraint $constraint): Constraint
    {
        /** @var Assert\Type $constraint */

        return new ParsleyAssert\Number([
            'message' => $this->trans($constraint->message, ['{{ type }}' => static::TYPE]),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function supports(SymfonyConstraint $constraint): bool
    {
        return $constraint instanceof Assert\Type && static::TYPE === $constraint->type;
    }
}
