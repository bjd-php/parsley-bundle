<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator\ParsleyConstraints;

use JBen87\ParsleyBundle\Validator\ParsleyConstraints\Length;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 *
 * bin/phpunit -c app/ src/JBen87/ParsleyBundle/Tests/Unit/Validator/ParsleyConstraints/LengthTest
 */
class LengthTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException JBen87\ParsleyBundle\Exception\Validator\ParsleyConstraints\MissingOptionsException
     */
    public function construct()
    {
        new Length();
    }
}
