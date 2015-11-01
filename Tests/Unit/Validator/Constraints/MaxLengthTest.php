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
}
