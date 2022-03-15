<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Tests\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use JBen87\ParsleyBundle\Constraint\Factory\FactoryInterface;
use JBen87\ParsleyBundle\Constraint\Factory\MaxLengthFactory;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

final class MaxLengthFactoryTest extends FactoryTestCase
{
    private const LIMIT = 10;
    private const ORIGINAL_MESSAGE = 'This value is too long. It should have {{ limit }} character or less.'
        .'|This value is too long. It should have {{ limit }} characters or less.'
    ;
    private const TRANSLATED_MESSAGE = 'This value is too long. It should have ' . self::LIMIT . ' characters or less.';

    protected function setUpCreate(): void
    {
        $this->translator
            ->expects($this->once())
            ->method('trans')
            ->with(
                self::ORIGINAL_MESSAGE,
                ['{{ limit }}' => self::LIMIT, '%count%' => self::LIMIT]
            )
            ->willReturn(self::TRANSLATED_MESSAGE)
        ;
    }

    protected function getExpectedConstraint(): Constraint
    {
        return new ParsleyAssert\MaxLength(['max' => self::LIMIT, 'message' => self::TRANSLATED_MESSAGE]);
    }

    protected function getOriginalConstraint(): SymfonyConstraint
    {
        return new Assert\Length(['max' => self::LIMIT]);
    }

    protected function getUnsupportedConstraint(): SymfonyConstraint
    {
        return new Assert\Valid();
    }

    protected function createFactory(): FactoryInterface
    {
        return new MaxLengthFactory();
    }
}
