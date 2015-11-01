<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator\ParsleyConstraints;

use JBen87\ParsleyBundle\Validator\Constraints\Length;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class LengthTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function emptyConfiguration()
    {
        new Length();
    }

    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function incompleteConfiguration()
    {
        new Length([
            'min' => 5,
        ]);
    }

    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function invalidConfiguration()
    {
        new Length([
            'min' => '5',
            'max' => '10',
        ]);
    }

    /**
     * @test
     */
    public function validConfiguration()
    {
        new Length([
            'min' => 5,
            'max' => 10,
        ]);

        new Length([
            'min' => 5,
            'max' => 10,
            'message' => 'Invalid',
        ]);
    }
}
