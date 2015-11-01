<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator\ParsleyConstraints;

use JBen87\ParsleyBundle\Validator\Constraints\Max;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class MaxTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function emptyConfiguration()
    {
        new Max();
    }

    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidArgumentException
     */
    public function invalidConfiguration()
    {
        new Max([
            'max' => '10',
        ]);
    }

    /**
     * @test
     */
    public function validConfiguration()
    {
        new Max([
            'max' => 10,
        ]);

        new Max([
            'max' => 10,
            'message' => 'Invalid',
        ]);
    }
}
