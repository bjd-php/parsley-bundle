<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

final class DateTimeFactory implements TranslatableFactoryInterface
{
    use FactoryTrait;

    private string $pattern;

    public function __construct(string $dateTimePattern)
    {
        $this->pattern = $dateTimePattern;
    }

    public function create(SymfonyConstraint $constraint): Constraint
    {
        /** @var Assert\DateTime $constraint */

        return new ParsleyAssert\Pattern([
            'pattern' => $this->pattern,
            'message' => $this->trans($constraint->message),
        ]);
    }

    public function supports(SymfonyConstraint $constraint): bool
    {
        return $constraint instanceof Assert\DateTime;
    }
}
