<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Factory;

use JBen87\ParsleyBundle\Factory\FactoryInterface;
use JBen87\ParsleyBundle\Factory\GreaterThanFactory;
use JBen87\ParsleyBundle\Validator\Constraint as ParsleyConstraint;
use JBen87\ParsleyBundle\Validator\Constraints as ParsleyAssert;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class GreaterThanFactoryTest extends FactoryTestCase
{
    private const VALUE = 10;
    private const ORIGINAL_MESSAGE = 'This value should be greater than {{ compared_value }}.';
    private const TRANSLATED_MESSAGE = 'This value should be greater than '.self::VALUE.'.';

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
        return new ParsleyAssert\GreaterThan(['value' => static::VALUE, 'message' => static::TRANSLATED_MESSAGE]);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginalConstraint(): SymfonyConstraint
    {
        return new Assert\GreaterThan(static::VALUE);
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
        return new GreaterThanFactory();
    }
}
