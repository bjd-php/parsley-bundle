<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator\ParsleyConstraints;

use JBen87\ParsleyBundle\Validator\Constraints\Range;

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
     * @expectedException \Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException
     */
    public function extraConfiguration()
    {
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
}
