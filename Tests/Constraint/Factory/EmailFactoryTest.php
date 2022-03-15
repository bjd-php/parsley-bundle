<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use JBen87\ParsleyBundle\Constraint\Factory\EmailFactory;
use JBen87\ParsleyBundle\Constraint\Factory\FactoryInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

final class EmailFactoryTest extends FactoryTestCase
{
    private const ORIGINAL_MESSAGE = 'This value is not a valid email address.';
    private const TRANSLATED_MESSAGE = 'This value is not a valid email address.';

    protected function setUpCreate(): void
    {
        $this->translator
            ->expects($this->once())
            ->method('trans')
            ->with(self::ORIGINAL_MESSAGE)
            ->willReturn(self::TRANSLATED_MESSAGE)
        ;
    }

    protected function getExpectedConstraint(): Constraint
    {
        return new ParsleyAssert\Email(['message' => self::TRANSLATED_MESSAGE]);
    }

    protected function getOriginalConstraint(): SymfonyConstraint
    {
        return new Assert\Email();
    }

    protected function getUnsupportedConstraint(): SymfonyConstraint
    {
        return new Assert\Valid();
    }

    protected function createFactory(): FactoryInterface
    {
        return new EmailFactory();
    }
}
