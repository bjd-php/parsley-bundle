<?php

namespace JBen87\ParsleyBundle\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

final class DateFactory implements TranslatableFactoryInterface
{
    use FactoryTrait;

    private string $pattern;

    public function __construct(string $datePattern)
    {
        $this->pattern = $datePattern;
    }

    public function create(SymfonyConstraint $constraint): Constraint
    {
        /** @var Assert\Date $constraint */

        return new ParsleyAssert\Pattern([
            'pattern' => $this->pattern,
            'message' => $this->trans($constraint->message),
        ]);
    }

    public function supports(SymfonyConstraint $constraint): bool
    {
        return $constraint instanceof Assert\Date;
    }
}
