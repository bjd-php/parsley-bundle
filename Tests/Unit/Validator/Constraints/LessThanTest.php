<?php

namespace Tests\Unit\Validator\Constraints;

use JBen87\ParsleyBundle\Tests\Unit\Validator\Constraint;
use JBen87\ParsleyBundle\Validator\Constraints\LessThan;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class LessThanTest extends Constraint
{
    /**
     * {@inheritdoc}
     *
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function emptyConfiguration()
    {
        new LessThan();
    }

    /**
     * {@inheritdoc}
     *
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function invalidConfiguration()
    {
        new LessThan([
            'value' => '10',
        ]);
    }

    /**
     * {@inheritdoc}
     *
     * @test
     */
    public function validConfiguration()
    {
        new LessThan([
            'value' => 10,
        ]);

        new LessThan([
            'value' => 10,
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
        $constraint = new LessThan([
            'value' => 10,
        ]);

        $this->assertSame([
            'data-parsley-lt' => 10,
            'data-parsley-lt-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer->reveal()));
    }
}
