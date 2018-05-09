<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use JBen87\ParsleyBundle\Constraint\Factory\ChainFactory;
use JBen87\ParsleyBundle\Constraint\Factory\FactoryInterface;
use JBen87\ParsleyBundle\Constraint\Factory\RequiredFactory;
use JBen87\ParsleyBundle\Exception\ConstraintException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints as Assert;

class ChainFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $expected = new ParsleyAssert\Required();

        $requiredFactory = $this->createMock(RequiredFactory::class);
        $requiredFactory
            ->expects($this->once())
            ->method('supports')
            ->willReturn(true)
        ;
        $requiredFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($expected)
        ;

        $factory = $this->createFactory([
            $requiredFactory,
        ]);

        $constraint = $factory->create(new Assert\NotBlank());
        $this->assertSame($expected, $constraint);
    }

    public function testCreateUnsupported(): void
    {
        $this->expectException(ConstraintException::class);
        $this->createFactory([])->create(new Assert\NotBlank());
    }

    public function testSupports(): void
    {
        $factory = $this->createFactory([]);

        $this->assertTrue($factory->supports(new Assert\NotBlank()));
        $this->assertTrue($factory->supports(new Assert\Valid()));
    }

    /**
     * @param FactoryInterface[] $factories
     *
     * @return ChainFactory
     */
    private function createFactory(array $factories): ChainFactory
    {
        return new ChainFactory($factories);
    }
}
