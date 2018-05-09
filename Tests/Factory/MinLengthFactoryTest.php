<?php

namespace JBen87\ParsleyBundle\Tests\Factory;

use JBen87\ParsleyBundle\Factory\FactoryInterface;
use JBen87\ParsleyBundle\Factory\MinLengthFactory;
use JBen87\ParsleyBundle\Validator\Constraint as ParsleyConstraint;
use JBen87\ParsleyBundle\Validator\Constraints as ParsleyAssert;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class MinLengthFactoryTest extends FactoryTestCase
{
    private const LIMIT = 10;
    private const ORIGINAL_MESSAGE = 'This value is too short. It should have {{ limit }} character or more.|This value is too short. It should have {{ limit }} characters or more.';
    private const TRANSLATED_MESSAGE = 'This value is too short. It should have '.self::LIMIT.' characters or more.';

    /**
     * @inheritdoc
     */
    protected function setUpCreate(): void
    {
        $this->translator
            ->expects($this->once())
            ->method('transChoice')
            ->with(static::ORIGINAL_MESSAGE, static::LIMIT, ['{{ limit }}' => static::LIMIT])
            ->willReturn(static::TRANSLATED_MESSAGE)
        ;
    }

    /**
     * @inheritdoc
     */
    protected function getExpectedConstraint(): ParsleyConstraint
    {
        return new ParsleyAssert\MinLength(['min' => static::LIMIT, 'message' => static::TRANSLATED_MESSAGE]);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginalConstraint(): SymfonyConstraint
    {
        return new Assert\Length(['min' => static::LIMIT]);
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
        return new MinLengthFactory();
    }
}
