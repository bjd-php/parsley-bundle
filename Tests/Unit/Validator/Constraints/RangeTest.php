<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator\ParsleyConstraints;

use JBen87\ParsleyBundle\Validator\Constraints\Range;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class RangeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function emptyConfiguration()
    {
        new Range();
    }

    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function incompleteConfiguration()
    {
        new Range([
            'min' => 5,
            'maxMessage' => 'Too long',
        ]);
    }

    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function invalidConfiguration()
    {
        new Range([
            'min' => '5',
            'max' => '10',
        ]);
    }

    /**
     * @test
     */
    public function extraConfiguration()
    {
        // handle symfony version <= 2.5
        if (method_exists(new OptionsResolver, 'remove')) {
            $this->setExpectedException('Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException');
        }

        new Range([
            'min' => 5,
            'max' => 10,
            'message' => 'Invalid',
        ]);
    }

    /**
     * @test
     */
    public function validConfiguration()
    {
        new Range([
            'min' => 5,
            'max' => 10,
        ]);

        new Range([
            'min' => 5,
            'max' => 10,
            'minMessage' => 'Too short',
            'maxMessage' => 'Too long',
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

        $constraint = new Range([
            'min' => 5,
            'max' => 10,
        ]);

        $this->assertEquals([
            'data-parsley-min' => '5',
            'data-parsley-min-message' => 'Invalid.',
            'data-parsley-max' => '10',
            'data-parsley-max-message' => 'Invalid.',
        ], $constraint->normalize($normalizer->reveal(), 'array'));
    }
}
