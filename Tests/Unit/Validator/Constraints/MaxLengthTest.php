<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator\Constraints;

use JBen87\ParsleyBundle\Tests\Unit\Validator\Constraint;
use JBen87\ParsleyBundle\Validator\Constraints\MaxLength;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class MaxLengthTest extends Constraint
{
    /**
     * {@inheritdoc}
     *
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function emptyConfiguration()
    {
        new MaxLength();
    }

    /**
     * {@inheritdoc}
     *
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function invalidConfiguration()
    {
        new MaxLength([
            'max' => '10',
        ]);
    }

    /**
     * {@inheritdoc}
     *
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

    /**
     * {@inheritdoc}
     *
     * @test
     */
    public function normalization()
    {
        $constraint = new MaxLength([
            'max' => 10,
        ]);

        $this->assertSame([
            'data-parsley-maxlength' => 10,
            'data-parsley-maxlength-message' => 'Invalid.',
            'maxlength' => 10,
        ], $constraint->normalize($this->normalizer->reveal()));
    }
}
