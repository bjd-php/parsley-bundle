<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use JBen87\ParsleyBundle\Constraint\Factory\EmailFactory;
use JBen87\ParsleyBundle\Constraint\Factory\FactoryInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class EmailFactoryTest extends FactoryTestCase
{
    private const ORIGINAL_MESSAGE = 'This value is not a valid email address.';
    private const TRANSLATED_MESSAGE = 'This value is not a valid email address.';

    /**
     * @inheritdoc
     */
    protected function setUpCreate(): void
    {
        $this->translator
            ->expects($this->once())
            ->method('trans')
            ->with(static::ORIGINAL_MESSAGE)
            ->willReturn(static::TRANSLATED_MESSAGE)
        ;
    }

    /**
     * @inheritdoc
     */
    protected function getExpectedConstraint(): Constraint
    {
        return new ParsleyAssert\Email(['message' => static::TRANSLATED_MESSAGE]);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginalConstraint(): SymfonyConstraint
    {
        return new Assert\Email();
    }

    /**
     * @inheritdoc
     */
    protected function getUnsupportedConstraint(): SymfonyConstraint
    {
        return new Assert\Valid();
    }

    /**
     * @inheritdoc
     */
    protected function createFactory(): FactoryInterface
    {
        return new EmailFactory();
    }
}
