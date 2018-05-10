<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use JBen87\ParsleyBundle\Constraint\Factory\FactoryInterface;
use JBen87\ParsleyBundle\Constraint\Factory\RangeFactory;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class RangeFactoryTest extends FactoryTestCase
{
    private const MIN = 5;
    private const MAX = 10;
    private const ORGIGINAL_MIN_MESSAGE = 'This value should be {{ limit }} or more.';
    private const TRANSLATED_MIN_MESSAGE = 'This value should be '.self::MIN.' or more.';
    private const ORGIGINAL_MAX_MESSAGE = 'This value should be {{ limit }} or less.';
    private const TRANSLATED_MAX_MESSAGE = 'This value should be '.self::MAX.' or less.';

    /**
     * @inheritdoc
     */
    protected function setUpCreate(): void
    {
        $this->translator
            ->expects($this->exactly(2))
            ->method('trans')
            ->withConsecutive([static::ORGIGINAL_MIN_MESSAGE], [static::ORGIGINAL_MAX_MESSAGE])
            ->willReturnOnConsecutiveCalls(static::TRANSLATED_MIN_MESSAGE, static::TRANSLATED_MAX_MESSAGE)
        ;
    }

    /**
     * @inheritdoc
     */
    protected function getExpectedConstraint(): Constraint
    {
        return new ParsleyAssert\Range([
            'min' => static::MIN,
            'max' => static::MAX,
            'minMessage' => static::TRANSLATED_MIN_MESSAGE,
            'maxMessage' => static::TRANSLATED_MAX_MESSAGE,
        ]);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginalConstraint(): SymfonyConstraint
    {
        return new Assert\Range(['min' => static::MIN, 'max' => static::MAX]);
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
        return new RangeFactory();
    }
}
