<?php

namespace JBen87\ParsleyBundle\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

final class TimeFactory implements TranslatableFactoryInterface
{
    use FactoryTrait;

    /**
     * @var string
     */
    private $pattern;

    /**
     * @param string $timePattern
     */
    public function __construct(string $timePattern)
    {
        $this->pattern = $timePattern;
    }

    /**
     * @inheritdoc
     */
    public function create(SymfonyConstraint $constraint): Constraint
    {
        /** @var Assert\Time $constraint */

        return new ParsleyAssert\Pattern([
            'pattern' => $this->pattern,
            'message' => $this->trans($constraint->message),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function supports(SymfonyConstraint $constraint): bool
    {
        return $constraint instanceof Assert\Time;
    }
}
