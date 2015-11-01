<?php

namespace JBen87\ParsleyBundle\Tests\Builder;

use JBen87\ParsleyBundle\Builder\ConstraintBuilder;
use JBen87\ParsleyBundle\Validator\Constraints as ParsleyAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class ConstraintBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConstraintBuilder
     */
    private $builder;

    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function emptyConfiguration()
    {
        $this->builder->configure([]);
    }

    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function invalidConfiguration()
    {
        $this->builder->configure([
            'constraints' => new Assert\NotBlank(),
        ]);
    }

    /**
     * @test
     * @expectedException \JBen87\ParsleyBundle\Exception\Builder\InvalidConfigurationException
     */
    public function invalidCreation()
    {
        $this->builder->build();
    }

    /**
     * @test
     */
    public function validCreation()
    {
        $this->builder->configure([
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Email(),
            ],
        ]);

        $this->assertEquals([
            new ParsleyAssert\Required(),
            new ParsleyAssert\Type(['type' => 'email']),
        ], $this->builder->build());
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $factory = $this->prophesize('JBen87\ParsleyBundle\Validator\ConstraintFactory');
        $factory->create(new Assert\NotBlank())->willReturn(new ParsleyAssert\Required());
        $factory->create(new Assert\Email())->willReturn(new ParsleyAssert\Type(['type' => 'email']));

        $this->builder = new ConstraintBuilder($factory->reveal());
    }
}
