<?php

namespace Tests\Unit\Validator\Constraints;

use JBen87\ParsleyBundle\Tests\Unit\Validator\Constraint;
use JBen87\ParsleyBundle\Validator\Constraints\GreaterThan;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class GreaterThanTest extends Constraint
{
    /**
     * {@inheritdoc}
     *
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function emptyConfiguration()
    {
        new GreaterThan();
    }

    /**
     * {@inheritdoc}
     *
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function invalidConfiguration()
    {
        new GreaterThan([
            'value' => '5',
        ]);
    }

    /**
     * {@inheritdoc}
     *
     * @test
     */
    public function validConfiguration()
    {
        new GreaterThan([
            'value' => 5,
        ]);

        new GreaterThan([
            'value' => 5,
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
        $constraint = new GreaterThan([
            'value' => 5,
        ]);

        $this->assertSame([
            'data-parsley-gt' => 5,
            'data-parsley-gt-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer->reveal()));
    }
}
