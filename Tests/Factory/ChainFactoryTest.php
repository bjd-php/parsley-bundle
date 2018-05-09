<?php

namespace JBen87\ParsleyBundle\Tests\Factory;

use JBen87\ParsleyBundle\Exception\Validator\ConstraintException;
use JBen87\ParsleyBundle\Factory\ChainFactory;
use JBen87\ParsleyBundle\Factory\FactoryInterface;
use JBen87\ParsleyBundle\Factory\RequiredFactory;
use JBen87\ParsleyBundle\Validator\Constraints as ParsleyAssert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ChainFactoryTest extends TestCase
{
    /**
     * @var MockObject|TranslatorInterface
     */
    private $translator;

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
     * @inheritdoc
     */
    protected function setUp(): void
    {
        $this->translator = $this->createMock(TranslatorInterface::class);
    }

    /**
     * @param FactoryInterface[] $factories
     *
     * @return ChainFactory
     */
    private function createFactory(array $factories): ChainFactory
    {
        return new ChainFactory($factories, $this->translator);
    }
}
