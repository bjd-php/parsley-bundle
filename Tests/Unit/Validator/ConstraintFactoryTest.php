<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator;

use JBen87\ParsleyBundle\Validator\ConstraintFactory;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class ConstraintFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConstraintFactory
     */
    private $factory;

    /**
     * @test
     * @expectedException \JBen87\ParsleyBundle\Exception\Validator\UnsupportedConstraintException
     */
    public function invalidConstraintCreation()
    {
        $this->factory->create(new Assert\Iban());
    }

    /**
     * @test
     */
    public function validConstraintCreation()
    {
        $this->assertInstanceOf(
            'JBen87\ParsleyBundle\Validator\Constraints\Type',
            $this->factory->create(new Assert\Email())
        );

        $this->assertInstanceOf(
            'JBen87\ParsleyBundle\Validator\Constraints\Length',
            $this->factory->create(new Assert\Length(5))
        );

        $this->assertInstanceOf(
            'JBen87\ParsleyBundle\Validator\Constraints\Length',
            $this->factory->create(new Assert\Length([
                'min' => 5,
                'max' => 10,
            ]))
        );

        $this->assertInstanceOf(
            'JBen87\ParsleyBundle\Validator\Constraints\MinLength',
            $this->factory->create(new Assert\Length([
                'min' => 5,
            ]))
        );

        $this->assertInstanceOf(
            'JBen87\ParsleyBundle\Validator\Constraints\MaxLength',
            $this->factory->create(new Assert\Length([
                'max' => 10,
            ]))
        );

        $this->assertInstanceOf(
            'JBen87\ParsleyBundle\Validator\Constraints\Required',
            $this->factory->create(new Assert\NotBlank())
        );

        $this->assertInstanceOf(
            'JBen87\ParsleyBundle\Validator\Constraints\Range',
            $this->factory->create(new Assert\Range([
                'min' => 5,
                'max' => 10,
            ]))
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $translator = $this->prophesize('Symfony\Component\Translation\Translator');

        $this->factory = new ConstraintFactory($translator->reveal());
    }
}
