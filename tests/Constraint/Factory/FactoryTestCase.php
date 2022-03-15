<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Tests\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Factory\FactoryInterface;
use JBen87\ParsleyBundle\Constraint\Factory\TranslatableFactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class FactoryTestCase extends TestCase
{
    protected ?FactoryInterface $factory = null;

    /**
     * @var MockObject|TranslatorInterface|null
     */
    protected ?MockObject $translator = null;

    /**
     * @dataProvider provideCreate
     */
    public function testCreate(Constraint $expected, SymfonyConstraint $constraint, callable $setup = null): void
    {
        if (is_callable($setup)) {
            $setup($this);
        } else {
            $this->setUpCreate();
        }

        $this->assertEquals($expected, $this->factory->create($constraint));
    }

    public function provideCreate(): array
    {
        return [
            [
                $this->getExpectedConstraint(),
                $this->getOriginalConstraint(),
            ],
        ];
    }

    /**
     * @dataProvider provideSupports
     */
    public function testSupports(SymfonyConstraint $supported, SymfonyConstraint $unsupported): void
    {
        $this->assertFalse($this->factory->supports($unsupported));
        $this->assertTrue($this->factory->supports($supported));
    }

    public function provideSupports(): array
    {
        return [
            [
                $this->getOriginalConstraint(),
                $this->getUnsupportedConstraint(),
            ],
        ];
    }

    protected function setUp(): void
    {
        $this->factory = $this->createFactory();
        $this->translator = $this->createMock(TranslatorInterface::class);

        if ($this->factory instanceof TranslatableFactoryInterface) {
            $this->factory->setTranslator($this->translator);
        }
    }

    protected function setUpCreate(): void
    {
    }

    protected function getExpectedConstraint(): Constraint
    {
        throw new \RuntimeException('Not implemented yet.');
    }

    protected function getUnsupportedConstraint(): SymfonyConstraint
    {
        throw new \RuntimeException('Not implemented yet.');
    }

    protected function getOriginalConstraint(): SymfonyConstraint
    {
        throw new \RuntimeException('Not implemented yet.');
    }

    abstract protected function createFactory(): FactoryInterface;
}
