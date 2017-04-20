<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator\Constraints;

use JBen87\ParsleyBundle\Tests\Unit\Validator\Constraint;
use JBen87\ParsleyBundle\Validator\Constraints\Max;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class MaxTest extends Constraint
{
    /**
     * {@inheritdoc}
     *
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function emptyConfiguration()
    {
        new Max();
    }

    /**
     * {@inheritdoc}
     *
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function invalidConfiguration()
    {
        new Max([
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
        new Max([
            'max' => 10,
        ]);

        new Max([
            'max' => 10,
            'message' => 'Invalid',
        ]);

        new Max([
            'max' => '2017-04-20 00:30',
            'message' => 'Invalid datetime',
        ]);
    }

    /**
     * {@inheritdoc}
     *
     * @test
     */
    public function normalization()
    {
        $constraint = new Max([
            'max' => 10,
        ]);

        $this->assertSame([
            'data-parsley-max' => 10,
            'data-parsley-max-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer->reveal()));
    }
}
