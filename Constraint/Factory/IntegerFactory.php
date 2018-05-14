<?php

namespace JBen87\ParsleyBundle\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class IntegerFactory implements TranslatableFactoryInterface
{
    use FactoryTrait;

    const TYPE = 'integer';
    const TYPE_ALT = 'int';

    /**
     * @inheritdoc
     */
    public function create(SymfonyConstraint $constraint): Constraint
    {
        /** @var Assert\Type $constraint */

        return new ParsleyAssert\Integer([
            'message' => $this->trans($constraint->message, ['{{ type }}' => static::TYPE]),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function supports(SymfonyConstraint $constraint): bool
    {
        return $constraint instanceof Assert\Type
            && (static::TYPE === $constraint->type || static::TYPE_ALT === $constraint->type)
        ;
    }
}
