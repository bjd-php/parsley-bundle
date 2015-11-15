<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator\Constraints;

use JBen87\ParsleyBundle\Tests\Unit\Validator\Constraint;
use JBen87\ParsleyBundle\Validator\Constraints\MinLength;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class MinLengthTest extends Constraint
{
    /**
     * {@inheritdoc}
     *
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function emptyConfiguration()
    {
        new MinLength();
    }

    /**
     * {@inheritdoc}
     *
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function invalidConfiguration()
    {
        new MinLength([
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
        new MinLength([
            'min' => 5,
        ]);

        new MinLength([
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
        $constraint = new MinLength([
            'min' => 5,
        ]);

        $this->assertSame([
            'data-parsley-minlength' => 5,
            'data-parsley-minlength-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer->reveal()));
    }
}
