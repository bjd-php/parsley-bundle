<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Factory;

use JBen87\ParsleyBundle\Factory\DateTimeFactory;
use JBen87\ParsleyBundle\Factory\FactoryInterface;
use JBen87\ParsleyBundle\Validator\Constraint as ParsleyConstraint;
use JBen87\ParsleyBundle\Validator\Constraints as ParsleyAssert;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class DateTimeFactoryTest extends FactoryTestCase
{
    private const PATTERN = 'Y-m-d H:i:s';
    private const ORIGINAL_MESSAGE = 'This value is not a valid datetime.';
    private const TRANSLATED_MESSAGE = 'This value is not a valid datetime.';

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
    protected function getExpectedConstraint(): ParsleyConstraint
    {
        return new ParsleyAssert\Pattern(['pattern' => static::PATTERN, 'message' => static::TRANSLATED_MESSAGE]);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginalConstraint(): SymfonyConstraint
    {
        return new Assert\DateTime();
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
        return new DateTimeFactory(static::PATTERN);
    }
}
