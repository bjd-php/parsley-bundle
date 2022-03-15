<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use JBen87\ParsleyBundle\Constraint\Factory\FactoryInterface;
use JBen87\ParsleyBundle\Constraint\Factory\LessThanFactory;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

final class LessThanFactoryTest extends FactoryTestCase
{
    private const VALUE = 10;
    private const ORIGINAL_MESSAGE = 'This value should be less than {{ compared_value }}.';
    private const TRANSLATED_MESSAGE = 'This value should be less than ' . self::VALUE . '.';

    protected function setUpCreate(): void
    {
        $this->translator
            ->expects($this->once())
            ->method('trans')
            ->with(self::ORIGINAL_MESSAGE, ['{{ compared_value }}' => self::VALUE])
            ->willReturn(self::TRANSLATED_MESSAGE)
        ;
    }

    protected function getExpectedConstraint(): Constraint
    {
        return new ParsleyAssert\LessThan(['value' => self::VALUE, 'message' => self::TRANSLATED_MESSAGE]);
    }

    protected function getOriginalConstraint(): SymfonyConstraint
    {
        return new Assert\LessThan(self::VALUE);
    }

    protected function getUnsupportedConstraint(): SymfonyConstraint
    {
        return new Assert\Valid();
    }

    protected function createFactory(): FactoryInterface
    {
        return new LessThanFactory();
    }
}
