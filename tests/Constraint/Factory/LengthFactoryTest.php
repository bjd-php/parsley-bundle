<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Tests\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use JBen87\ParsleyBundle\Constraint\Factory\FactoryInterface;
use JBen87\ParsleyBundle\Constraint\Factory\LengthFactory;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

final class LengthFactoryTest extends FactoryTestCase
{
    private const MIN = 5;
    private const MAX = 10;
    private const LIMIT = 10;
    private const ORIGINAL_MESSAGE = 'This value should have {{ min }} to {{ max }} characters.';
    private const TRANSLATED_MESSAGE = 'This value should have ' . self::MIN . ' to ' . self::MIN . ' characters.';
    private const ORIGINAL_EXACT_MESSAGE = 'This value should have exactly {{ limit }} character.'
        . '|This value should have exactly {{ limit }} characters.'
    ;
    private const TRANSLATED_EXACT_MESSAGE = 'This value should have exactly ' . self::LIMIT . ' characters.';

    public function provideCreate(): array
    {
        return [
            [
                new ParsleyAssert\Length([
                    'min' => self::MIN,
                    'max' => self::MAX,
                    'message' => self::TRANSLATED_MESSAGE,
                ]),
                new Assert\Length([
                    'min' => self::MIN,
                    'max' => self::MAX,
                ]),
                function (LengthFactoryTest $self): void {
                    $self->translator
                        ->expects($this->once())
                        ->method('trans')
                        ->with(
                            self::ORIGINAL_MESSAGE,
                            ['{{ min }}' => self::MIN, '{{ max }}' => self::MAX]
                        )
                        ->willReturn(self::TRANSLATED_MESSAGE)
                    ;
                },
            ],
            [
                new ParsleyAssert\Length([
                    'min' => self::LIMIT,
                    'max' => self::LIMIT,
                    'message' => self::TRANSLATED_EXACT_MESSAGE,
                ]),
                new Assert\Length([
                    'min' => self::LIMIT,
                    'max' => self::LIMIT,
                ]),
                function (LengthFactoryTest $self): void {
                    $self->translator
                        ->expects($this->exactly(2))
                        ->method('trans')
                        ->withConsecutive(
                            [
                                self::ORIGINAL_MESSAGE,
                                ['{{ min }}' => self::LIMIT, '{{ max }}' => self::LIMIT],
                            ],
                            [
                                self::ORIGINAL_EXACT_MESSAGE,
                                ['{{ limit }}' => self::LIMIT, '%count%' => self::LIMIT],
                            ]
                        )
                        ->willReturnOnConsecutiveCalls(
                            sprintf('This value should have %d to %d characters.', self::MAX, self::MIN),
                            self::TRANSLATED_EXACT_MESSAGE
                        )
                    ;
                },
            ],
        ];
    }

    protected function setUpCreate(): void
    {
        $this->translator
            ->expects($this->once())
            ->method('trans')
            ->with(self::ORIGINAL_MESSAGE, ['{{ min }}' => self::MIN, '{{ max }}' => self::MAX])
            ->willReturn(self::TRANSLATED_MESSAGE)
        ;
    }

    protected function getOriginalConstraint(): SymfonyConstraint
    {
        return new Assert\Length([
            'min' => self::MIN,
            'max' => self::MAX,
        ]);
    }

    protected function getUnsupportedConstraint(): SymfonyConstraint
    {
        return new Assert\Valid();
    }

    protected function createFactory(): FactoryInterface
    {
        return new LengthFactory();
    }
}
