<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator\ParsleyConstraints;

use JBen87\ParsleyBundle\Validator\Constraints\Type;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class TypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function emptyConfiguration()
    {
        new Type();
    }

    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function invalidConfiguration()
    {
        new Type([
            'type' => 'string',
        ]);
    }

    /**
     * @test
     */
    public function validConfiguration()
    {
        new Type([
            'type' => 'email',
        ]);

        new Type([
            'type' => 'email',
            'message' => 'Invalid',
        ]);
    }

    /**
     * @test
     */
    public function normalization()
    {
        if (class_exists('Symfony\Component\Serializer\Normalizer\ObjectNormalizer')) {
            $normalizer = $this->prophesize('Symfony\Component\Serializer\Normalizer\ObjectNormalizer');
        } else {
            $normalizer = $this->prophesize('Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer');
        }

        $constraint = new Type([
            'type' => 'email',
        ]);

        $this->assertEquals([
            'data-parsley-type' => 'email',
            'data-parsley-type-message' => 'Invalid.',
        ], $constraint->normalize($normalizer->reveal(), 'array'));
    }
}
