<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use JBen87\ParsleyBundle\Constraint\Factory\FactoryInterface;
use JBen87\ParsleyBundle\Constraint\Factory\LengthFactory;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class LengthFactoryTest extends FactoryTestCase
{
    private const MIN = 5;
    private const MAX = 10;
    private const LIMIT = 10;
    private const ORIGINAL_MESSAGE = 'This value should have {{ min }} to {{ max }} characters.';
    private const TRANSLATED_MESSAGE = 'This value should have '.self::MIN.' to '.self::MIN.' characters.';
    private const ORIGINAL_EXACT_MESSAGE = 'This value should have exactly {{ limit }} character.'
        .'|This value should have exactly {{ limit }} characters.'
    ;
    private const TRANSLATED_EXACT_MESSAGE = 'This value should have exactly '.self::LIMIT.' characters.';

    /**
     * @inheritdoc
     */
    public function provideCreate(): array
    {
        return [
            [
                new ParsleyAssert\Length([
                    'min' => static::MIN,
                    'max' => static::MAX,
                    'message' => static::TRANSLATED_MESSAGE,
                ]),
                new Assert\Length([
                    'min' => static::MIN,
                    'max' => static::MAX,
                ]),
                function (LengthFactoryTest $self): void {
                    $self->translator
                        ->expects($this->once())
                        ->method('trans')
                        ->with(
                            static::ORIGINAL_MESSAGE,
                            ['{{ min }}' => static::MIN, '{{ max }}' => static::MAX]
                        )
                        ->willReturn(static::TRANSLATED_MESSAGE)
                    ;
                },
            ],
            [
                new ParsleyAssert\Length([
                    'min' => static::LIMIT,
                    'max' => static::LIMIT,
                    'message' => static::TRANSLATED_EXACT_MESSAGE,
                ]),
                new Assert\Length([
                    'min' => static::LIMIT,
                    'max' => static::LIMIT,
                ]),
                function (LengthFactoryTest $self): void {
                    $self->translator
                        ->expects($this->once())
                        ->method('trans')
                        ->with(
                            static::ORIGINAL_MESSAGE,
                            ['{{ min }}' => static::LIMIT, '{{ max }}' => static::LIMIT]
                        )
                        ->willReturn(sprintf('This value should have %d to %d characters.', static::MAX, static::MIN))
                    ;

                    $self->translator
                        ->expects($this->once())
                        ->method('transChoice')
                        ->with(
                            static::ORIGINAL_EXACT_MESSAGE,
                            static::LIMIT,
                            ['{{ limit }}' => static::LIMIT]
                        )
                        ->willReturn(static::TRANSLATED_EXACT_MESSAGE)
                    ;
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    protected function setUpCreate(): void
    {
        $this->translator
            ->expects($this->once())
            ->method('trans')
            ->with(static::ORIGINAL_MESSAGE, ['{{ min }}' => static::MIN, '{{ max }}' => static::MAX])
            ->willReturn(static::TRANSLATED_MESSAGE)
        ;
    }

    /**
     * @inheritdoc
     */
    protected function getOriginalConstraint(): SymfonyConstraint
    {
        return new Assert\Length([
            'min' => static::MIN,
            'max' => static::MAX,
        ]);
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
        return new LengthFactory();
    }
}
