<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator\ParsleyConstraints;

use JBen87\ParsleyBundle\Validator\Constraints\Min;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class MinTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function emptyConfiguration()
    {
        new Min();
    }

    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidArgumentException
     */
    public function invalidConfiguration()
    {
        new Min([
            'min' => '5',
        ]);
    }

    /**
     * @test
     */
    public function validConfiguration()
    {
        new Min([
            'min' => 5,
        ]);

        new Min([
            'min' => 5,
            'message' => 'Invalid',
        ]);
    }
}
