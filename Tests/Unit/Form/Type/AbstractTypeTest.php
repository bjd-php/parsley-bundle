<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Form\Type;

use JBen87\ParsleyBundle\Form\Adapter\ConstraintsAdapter;
use JBen87\ParsleyBundle\Tests\Resources\Form\Type\CustomType;
use JBen87\ParsleyBundle\Validator\ParsleyConstraints;
use JBen87\ParsleyBundle\Validator\ParsleyConstraints\ParsleyConstraint;
use Symfony\Component\Validator\Constraints;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 *
 * bin/phpunit -c app/ src/JBen87/ParsleyBundle/Tests/Unit/Form/Type/AbstractTypeTest.php
 */
class AbstractTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function generateConstraints()
    {
        $form = new CustomType($this->createConstraintsAdapter());

        $this->assertInstanceOf('JBen87\\ParsleyBundle\\Form\\Type\\AbstractType', $form);
        $this->assertEquals($form->getConstraints(), [
            'symfony'   => $form->getSymfonyConstraints(),
            'parsley'   => $this->getParsleyConstraints()
        ]);
    }

    /**
     * @return ConstraintsAdapter
     */
    protected function createConstraintsAdapter()
    {
        $adapter = $this->prophesize('JBen87\\ParsleyBundle\\Form\\Adapter\\ConstraintsAdapter');

        $adapter->generateConstraints([
            new Constraints\NotBlank(),
            new Constraints\Email()
        ])->willReturn([
            new ParsleyConstraints\Required(),
            new ParsleyConstraints\Type(['type' => 'email'])
        ]);

        return $adapter->reveal();
    }

    /**
     * @return ParsleyConstraint[]
     */
    protected function getParsleyConstraints()
    {
        return [
            'field' => [
                new ParsleyConstraints\Required(),
                new ParsleyConstraints\Type(['type' => 'email'])
            ]
        ];
    }
}
