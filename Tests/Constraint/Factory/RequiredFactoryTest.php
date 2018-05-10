<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use JBen87\ParsleyBundle\Constraint\Factory\FactoryInterface;
use JBen87\ParsleyBundle\Constraint\Factory\RequiredFactory;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class RequiredFactoryTest extends FactoryTestCase
{
    private const ORIGINAL_MESSAGE = 'This value should not be blank.';
    private const TRANSLATED_MESSAGE = 'This value should not be blank.';

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
        return new ParsleyAssert\Required(['message' => static::TRANSLATED_MESSAGE]);
    }

    /**
     * @inheritdoc
     */
    protected function getOriginalConstraint(): SymfonyConstraint
    {
        return new Assert\NotBlank();
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
        return new RequiredFactory();
    }
}
