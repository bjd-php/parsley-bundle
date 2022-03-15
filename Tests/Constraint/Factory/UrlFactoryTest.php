<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use JBen87\ParsleyBundle\Constraint\Factory\FactoryInterface;
use JBen87\ParsleyBundle\Constraint\Factory\UrlFactory;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

final class UrlFactoryTest extends FactoryTestCase
{
    private const ORIGINAL_MESSAGE = 'This value is not a valid URL.';
    private const TRANSLATED_MESSAGE = 'This value is not a valid URL.';

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
        return new ParsleyAssert\Url(['message' => self::TRANSLATED_MESSAGE]);
    }

    protected function getOriginalConstraint(): SymfonyConstraint
    {
        return new Assert\Url();
    }

    protected function getUnsupportedConstraint(): SymfonyConstraint
    {
        return new Assert\Valid();
    }

    protected function createFactory(): FactoryInterface
    {
        return new UrlFactory();
    }
}
