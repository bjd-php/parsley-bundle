<?php

namespace JBen87\ParsleyBundle\Tests\Builder;

use JBen87\ParsleyBundle\Builder\ConstraintBuilder;
use JBen87\ParsleyBundle\Factory\ConstraintFactory;
use JBen87\ParsleyBundle\Validator\Constraints as ParsleyAssert;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class ConstraintBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectProphecy|ConstraintFactory
     */
    private $factory;

    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function emptyConfiguration()
    {
        $this->createBuilder()->configure([]);
    }

    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function invalidConfiguration()
    {
        $this->createBuilder()->configure([
            'constraints' => new Assert\NotBlank(),
        ]);
    }

    /**
     * @test
     * @expectedException \JBen87\ParsleyBundle\Exception\Builder\InvalidConfigurationException
     */
    public function invalidCreation()
    {
        $this->createBuilder()->build();
    }

    /**
     * @param array $symfonyConstraints
     * @param array $expected
     *
     * @test
     * @dataProvider validProvider
     */
    public function valid(array $symfonyConstraints, array $expected)
    {
        foreach ($symfonyConstraints as $key => $constraint) {
            $this->factory->create($constraint)->shouldBeCalled()->willReturn($expected[$key]);
        }

        $builder = $this->createBuilder();

        $builder->configure(['constraints' => $symfonyConstraints]);
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
    public function validProvider()
    {
        return [
            [
                [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                ],
                [
                    new ParsleyAssert\Required(),
                    new ParsleyAssert\Type(['type' => 'email']),
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->factory = $this->prophesize('JBen87\ParsleyBundle\Factory\ConstraintFactory');
    }

    /**
     * @return ConstraintBuilder
     */
    private function createBuilder()
    {
        return new ConstraintBuilder($this->factory->reveal());
    }
}
