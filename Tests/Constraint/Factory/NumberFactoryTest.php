<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use JBen87\ParsleyBundle\Constraint\Factory\FactoryInterface;
use JBen87\ParsleyBundle\Constraint\Factory\NumberFactory;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

final class NumberFactoryTest extends FactoryTestCase
{
    private const ORIGINAL_MESSAGE = 'This value should be of type {{ type }}.';
    private const TRANSLATED_MESSAGE = 'This value should be of type numeric.';

    protected function setUpCreate(): void
    {
        $this->translator
            ->expects($this->once())
            ->method('trans')
            ->with(self::ORIGINAL_MESSAGE, ['{{ type }}' => 'numeric'])
            ->willReturn(self::TRANSLATED_MESSAGE)
        ;
    }

    protected function getExpectedConstraint(): Constraint
    {
        return new ParsleyAssert\Number(['message' => self::TRANSLATED_MESSAGE]);
    }

    protected function getOriginalConstraint(): SymfonyConstraint
    {
        return new Assert\Type(['type' => 'numeric']);
    }

    protected function getUnsupportedConstraint(): SymfonyConstraint
    {
        return new Assert\Valid();
    }

    protected function createFactory(): FactoryInterface
    {
        return new NumberFactory();
    }
}
