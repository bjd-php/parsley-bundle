<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator\Constraints;

use JBen87\ParsleyBundle\Tests\Unit\Validator\Constraint;
use JBen87\ParsleyBundle\Validator\Constraints\Min;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class MinTest extends Constraint
{
    /**
     * {@inheritdoc}
     *
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function emptyConfiguration()
    {
        new Min();
    }

    /**
     * {@inheritdoc}
     *
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function invalidConfiguration()
    {
        new Min([
            'min' => '5',
        ]);
    }

    /**
     * {@inheritdoc}
     *
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

    /**
     * {@inheritdoc}
     *
     * @test
     */
    public function normalization()
    {
        $constraint = new Min([
            'min' => 5,
        ]);

        $this->assertSame([
            'data-parsley-min' => 5,
            'data-parsley-min-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer->reveal()));
    }
}
