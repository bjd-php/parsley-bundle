<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Factory;

use JBen87\ParsleyBundle\Factory\ConstraintFactory;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Translation\TranslatorInterface;
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
     * @param array $data
     *
     * @test
     * @dataProvider validProvider
     */
    public function validConstraintCreation(array $data)
    {
        $this->assertInstanceOf($data['class'], $this->createFactory()->create($data['constraint']));
    }

    /**
     * @return array
     */
    public function validProvider()
    {
        return [
            [
                [
                    'class' => 'JBen87\ParsleyBundle\Validator\Constraints\Type',
                    'constraint' => new Assert\Email(),
                ],
            ],
            [
                [
                    'class' => 'JBen87\ParsleyBundle\Validator\Constraints\Length',
                    'constraint' => new Assert\Length(5),
                ],
            ],
            [
                [
                    'class' => 'JBen87\ParsleyBundle\Validator\Constraints\Length',
                    'constraint' => new Assert\Length(['min' => 5, 'max' => 10]),
                ],
            ],
            [
                [
                    'class' => 'JBen87\ParsleyBundle\Validator\Constraints\MinLength',
                    'constraint' => new Assert\Length(['min' => 5]),
                ],
            ],
            [
                [
                    'class' => 'JBen87\ParsleyBundle\Validator\Constraints\MaxLength',
                    'constraint' => new Assert\Length(['max' => 10]),
                ],
            ],
            [
                [
                    'class' => 'JBen87\ParsleyBundle\Validator\Constraints\Required',
                    'constraint' => new Assert\NotBlank(),
                ],
            ],
            [
                [
                    'class' => 'JBen87\ParsleyBundle\Validator\Constraints\Range',
                    'constraint' => new Assert\Range(['min' => 5, 'max' => 10]),
                ],
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
