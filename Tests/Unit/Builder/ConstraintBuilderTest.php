<?php

namespace JBen87\ParsleyBundle\Tests\Builder;

use JBen87\ParsleyBundle\Builder\ConstraintBuilder;
use JBen87\ParsleyBundle\Exception\Builder\InvalidConfigurationException;
use JBen87\ParsleyBundle\Factory\ConstraintFactory;
use JBen87\ParsleyBundle\Validator\Constraint as ParsleyConstraint;
use JBen87\ParsleyBundle\Validator\Constraints as ParsleyAssert;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class ConstraintBuilderTest extends TestCase
{
    /**
     * @var ObjectProphecy|ConstraintFactory
     */
    private $factory;

    public function testEmptyConfiguration(): void
    {
        $this->expectException(MissingOptionsException::class);

        $this->createBuilder()->configure([]);
    }

    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidOptionsException::class);

        $this->createBuilder()->configure([
            'constraints' => new Assert\NotBlank(),
        ]);
    }

    public function testInvalidCreation(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $this->createBuilder()->build();
    }

    /**
     * @param ParsleyConstraint[] $expected
     * @param SymfonyConstraint[] $constraints
     *
     * @dataProvider validProvider
     */
    public function testValid(array $expected, array $constraints): void
    {
        foreach ($constraints as $key => $constraint) {
            $this->factory->create($constraint)
                ->shouldBeCalled()
                ->willReturn($expected[$key])
            ;
        }

        $builder = $this->createBuilder();
        $builder->configure(['constraints' => $constraints]);

        $parsleyConstraints = $builder->build();

        $this->assertInternalType('array', $parsleyConstraints);
        $this->assertCount(count($expected), $parsleyConstraints);

        foreach ($parsleyConstraints as $key => $constraint) {
            $this->assertSame($expected[$key], $constraint);
        }
    }

    /**
     * @return array
     */
    public function validProvider(): array
    {
        return [
            [
                [
                    new ParsleyAssert\Required(),
                    new ParsleyAssert\Type(['type' => 'email']),
                ],
                [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        $this->factory = $this->prophesize(ConstraintFactory::class);
    }

    /**
     * @return ConstraintBuilder
     */
    private function createBuilder(): ConstraintBuilder
    {
        /** @var ConstraintFactory $factory */
        $factory = $this->factory->reveal();

        return new ConstraintBuilder($factory);
    }
}
