<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Factory;

use JBen87\ParsleyBundle\Factory\FactoryInterface;
use JBen87\ParsleyBundle\Factory\TranslatableFactoryInterface;
use JBen87\ParsleyBundle\Validator\Constraint as ParsleyConstraint;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

abstract class FactoryTestCase extends TestCase
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var MockObject|TranslatorInterface
     */
    protected $translator;

    /**
     * @param ParsleyConstraint $expected
     * @param SymfonyConstraint $constraint
     * @param callable|null $setup
     *
     * @dataProvider provideCreate
     */
    public function testCreate(ParsleyConstraint $expected, SymfonyConstraint $constraint, callable $setup = null): void
    {
        if (is_callable($setup)) {
            $setup($this);
        } else {
            $this->setUpCreate();
        }

        $this->assertEquals($expected, $this->factory->create($constraint));
    }

    /**
     * @return array
     */
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
     * @param SymfonyConstraint $supported
     * @param SymfonyConstraint $unsupported
     *
     * @dataProvider provideSupports
     */
    public function testSupports(SymfonyConstraint $supported, SymfonyConstraint $unsupported): void
    {
        $this->assertFalse($this->factory->supports($unsupported));
        $this->assertTrue($this->factory->supports($supported));
    }

    /**
     * @return array
     */
    public function provideSupports(): array
    {
        return [
            [
                $this->getOriginalConstraint(),
                $this->getUnsupportedConstraint(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
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

    /**
     * @return ParsleyConstraint
     */
    protected function getExpectedConstraint(): ParsleyConstraint
    {
        throw new \RuntimeException('Not implemented yet.');
    }

    /**
     * @return SymfonyConstraint
     */
    protected function getUnsupportedConstraint(): SymfonyConstraint
    {
        throw new \RuntimeException('Not implemented yet.');
    }
    /**
     * @return SymfonyConstraint
     */
    protected function getOriginalConstraint(): SymfonyConstraint
    {
        throw new \RuntimeException('Not implemented yet.');
    }

    /**
     * @return FactoryInterface
     */
    abstract protected function createFactory(): FactoryInterface;
}
