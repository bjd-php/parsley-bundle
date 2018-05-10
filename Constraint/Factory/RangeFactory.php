<?php

namespace JBen87\ParsleyBundle\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class RangeFactory implements TranslatableFactoryInterface
{
    use FactoryTrait;

    /**
     * @inheritdoc
     */
    public function create(SymfonyConstraint $constraint): Constraint
    {
        /** @var Assert\Range $constraint */

        $options = [];

        if (null !== $constraint->min) {
            $options += [
                'min' => $constraint->min,
                'minMessage' => $this->trans($constraint->minMessage, ['{{ limit }}' => $constraint->min]),
            ];
        }

        if (null !== $constraint->max) {
            $options += [
                'max' => $constraint->max,
                'maxMessage' => $this->trans($constraint->maxMessage, ['{{ limit }}' => $constraint->max]),
            ];
        }

        return new ParsleyAssert\Range($options);
    }

    /**
     * @inheritdoc
     */
    public function supports(SymfonyConstraint $constraint): bool
    {
        return $constraint instanceof Assert\Range;
    }
}
