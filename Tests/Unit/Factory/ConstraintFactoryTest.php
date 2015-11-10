<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Factory;

use JBen87\ParsleyBundle\Factory\ConstraintFactory;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class ConstraintFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectProphecy|TranslatorInterface
     */
    private $translator;

    /**
     * @test
     * @expectedException \JBen87\ParsleyBundle\Exception\Validator\UnsupportedConstraintException
     */
    public function invalidConstraintCreation()
    {
        $this->createFactory()->create(new Assert\Iban());
    }

    /**
     * @param string $class
     * @param Constraint $constraint
     *
     * @test
     * @dataProvider validProvider
     */
    public function validConstraintCreation($class, Constraint $constraint)
    {
        $this->assertInstanceOf($class, $this->createFactory()->create($constraint));
    }

    /**
     * @return array
     */
    public function validProvider()
    {
        return [
            [
                'JBen87\ParsleyBundle\Validator\Constraints\Type',
                new Assert\Email()
            ],
            [
                'JBen87\ParsleyBundle\Validator\Constraints\Length',
                new Assert\Length(5)
            ],
            [
                'JBen87\ParsleyBundle\Validator\Constraints\Length',
                new Assert\Length(['min' => 5, 'max' => 10])
            ],
            [
                'JBen87\ParsleyBundle\Validator\Constraints\MinLength',
                new Assert\Length(['min' => 5])
            ],
            [
                'JBen87\ParsleyBundle\Validator\Constraints\MaxLength',
                new Assert\Length(['max' => 10])
            ],
            [
                'JBen87\ParsleyBundle\Validator\Constraints\Required',
                new Assert\NotBlank()
            ],
            [
                'JBen87\ParsleyBundle\Validator\Constraints\Range',
                new Assert\Range(['min' => 5, 'max' => 10])
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->translator = $this->prophesize('Symfony\Component\Translation\Translator');
    }

    private function createFactory()
    {
        return new ConstraintFactory($this->translator->reveal());
    }
}
