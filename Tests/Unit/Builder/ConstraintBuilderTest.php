<?php

namespace JBen87\ParsleyBundle\Tests\Builder;

use JBen87\ParsleyBundle\Builder\ConstraintBuilder;
use JBen87\ParsleyBundle\Validator\ConstraintFactory;
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
     * @test
     * @dataProvider validCreationProvider
     */
    public function validCreation(array $constraints, array $expected)
    {
        if (count($constraints) !== count($expected)) {
            throw new \Exception('dummy');
        }

        foreach (array_keys($constraints) as $idx) {
            $this->factory->create($constraints[$idx])->shouldBeCalled()->willReturn($expected[$idx]);
        }

        $builder = $this->createBuilder();

        $builder->configure([
            'constraints' => $constraints,
        ]);

        $got = $builder->build();

        $this->assertInternalType('array', $got);
        $this->assertCount(count($expected), $got);

        foreach ($expected as $idx => $expectation) {
            $this->assertArrayHasKey($idx, $got);
            $this->assertSame($got[$idx], $expectation);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->factory = $this->prophesize('JBen87\ParsleyBundle\Validator\ConstraintFactory');
    }

    /**
     * @return ConstraintBuilder
     */
    private function createBuilder()
    {
        return new ConstraintBuilder($this->factory->reveal());
    }

    /**
     * @return array
     */
    public function validCreationProvider()
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
                ]
            ],
        ];
    }
}
