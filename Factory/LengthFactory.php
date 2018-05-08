<?php

namespace JBen87\ParsleyBundle\Factory;

use JBen87\ParsleyBundle\Validator\Constraint as ParsleyConstraint;
use JBen87\ParsleyBundle\Validator\Constraints as ParsleyAssert;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class LengthFactory implements TranslatableFactoryInterface
{
    use FactoryTrait;

    /**
     * @inheritdoc
     */
    public function create(SymfonyConstraint $constraint): ParsleyConstraint
    {
        /** @var Assert\Length $constraint */

        $options = [
            'min' => $constraint->min,
            'max' => $constraint->max,
            'message' => $this->trans(
                'This value should have {{ min }} to {{ max }} characters.',
                ['{{ min }}' => $constraint->min, '{{ max }}' => $constraint->max]
            ),
        ];

        if ($constraint->min === $constraint->max) {
            $options['message'] = $this->transChoice(
                $constraint->exactMessage,
                $constraint->min,
                ['{{ limit }}' => $constraint->min]
            );
        }

        return new ParsleyAssert\Length($options);
    }

    /**
     * @inheritdoc
     */
    public function supports(SymfonyConstraint $constraint): bool
    {
        return $constraint instanceof Assert\Length && null !== $constraint->min && null !== $constraint->max;
    }
}
