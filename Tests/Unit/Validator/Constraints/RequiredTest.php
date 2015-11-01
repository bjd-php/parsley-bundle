<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator\ParsleyConstraints;

use JBen87\ParsleyBundle\Validator\Constraints\Required;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class RequiredTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function validConfiguration()
    {
        new Required();

        new Required([
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

        $constraint = new Required();

        $this->assertEquals([
            'data-parsley-required' => 'true',
            'data-parsley-required-message' => 'Invalid.',
        ], $constraint->normalize($normalizer->reveal(), 'array'));
    }
}
