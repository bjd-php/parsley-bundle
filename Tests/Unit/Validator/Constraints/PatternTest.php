<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator\Constraints;

use JBen87\ParsleyBundle\Tests\Unit\Validator\Constraint;
use JBen87\ParsleyBundle\Validator\Constraints\Pattern;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class PatternTest extends Constraint
{
    /**
     * {@inheritdoc}
     *
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function emptyConfiguration()
    {
        new Pattern();
    }

    /**
     * {@inheritdoc}
     *
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function invalidConfiguration()
    {
        new Pattern([
            'pattern' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     *
     * @test
     */
    public function validConfiguration()
    {
        new Pattern([
            'pattern' => '\d',
        ]);

        new Pattern([
            'pattern' => '\w+',
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
        $constraint = new Pattern([
            'pattern' => '\w',
        ]);

        $this->assertSame([
            'data-parsley-pattern' => '\w',
            'data-parsley-pattern-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer->reveal()));
    }
}
