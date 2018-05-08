<?php

namespace JBen87\ParsleyBundle\Factory;

use JBen87\ParsleyBundle\Validator\Constraint as ParsleyConstraint;
use JBen87\ParsleyBundle\Validator\Constraints as ParsleyAssert;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class RequiredFactory implements TranslatableFactoryInterface
{
    use FactoryTrait;

    /**
     * @inheritdoc
     */
    public function create(SymfonyConstraint $constraint): ParsleyConstraint
    {
        /** @var Assert\NotBlank $constraint */

        return new ParsleyAssert\Required([
            'message' => $this->trans($constraint->message),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function supports(SymfonyConstraint $constraint): bool
    {
        return $constraint instanceof Assert\NotBlank;
    }
}
