<?php

namespace JBen87\ParsleyBundle\Factory;

use JBen87\ParsleyBundle\Validator\Constraint as ParsleyConstraint;
use JBen87\ParsleyBundle\Validator\Constraints as ParsleyAssert;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class MinLengthFactory implements TranslatableFactoryInterface
{
    use FactoryTrait;

    /**
     * @inheritdoc
     */
    public function create(SymfonyConstraint $constraint): ParsleyConstraint
    {
        /** @var Assert\Length $constraint */

        return new ParsleyAssert\MinLength([
            'min' => $constraint->min,
            'message' => $this->transChoice(
                $constraint->minMessage,
                $constraint->min,
                ['{{ limit }}' => $constraint->min]
            ),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function supports(SymfonyConstraint $constraint): bool
    {
        return $constraint instanceof Assert\Length && null !== $constraint->min && null === $constraint->max;
    }
}
