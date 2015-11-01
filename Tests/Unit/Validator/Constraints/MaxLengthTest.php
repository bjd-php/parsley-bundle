<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator\ParsleyConstraints;

use JBen87\ParsleyBundle\Validator\Constraints\MaxLength;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class MaxLengthTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function emptyConfiguration()
    {
        new MaxLength();
    }

    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidArgumentException
     */
    public function invalidConfiguration()
    {
        new MaxLength([
            'max' => '10',
        ]);
    }

    /**
     * @test
     */
    public function validConfiguration()
    {
        new MaxLength([
            'max' => 10,
        ]);

        new MaxLength([
            'max' => 10,
            'message' => 'Invalid',
        ]);
    }

    /**
     * @test
     */
    public function normalization()
    {
        $normalizer = $this->prophesize('Symfony\Component\Serializer\Normalizer\ObjectNormalizer');
        $constraint = new MaxLength([
            'max' => 10,
        ]);

        $this->assertEquals([
            'data-parsley-maxlength' => '10',
            'data-parsley-maxlength-message' => 'Invalid.',
        ], $constraint->normalize($normalizer->reveal(), 'array'));
    }
}
