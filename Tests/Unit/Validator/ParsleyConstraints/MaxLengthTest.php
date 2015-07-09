<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator\ParsleyConstraints;

use JBen87\ParsleyBundle\Validator\ParsleyConstraints\MaxLength;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 *
 * bin/phpunit -c app/ src/JBen87/ParsleyBundle/Tests/Unit/Validator/ParsleyConstraints/MaxLengthTest
 */
class MaxLengthTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException JBen87\ParsleyBundle\Exception\Validator\ParsleyConstraints\MissingOptionsException
     */
    public function construct()
    {
        new MaxLength();
    }
}
