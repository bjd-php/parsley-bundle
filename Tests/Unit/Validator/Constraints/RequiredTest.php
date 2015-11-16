<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator\Constraints;

use JBen87\ParsleyBundle\Tests\Unit\Validator\Constraint;
use JBen87\ParsleyBundle\Validator\Constraints\Required;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class RequiredTest extends Constraint
{
    /**
     * {@inheritdoc}
     *
     * @test
     */
    public function emptyConfiguration()
    {
        new Required();
    }

    /**
     * {@inheritdoc}
     */
    public function invalidConfiguration()
    {
    }

    /**
     * {@inheritdoc}
     *
     * @test
     */
    public function validConfiguration()
    {
        new Required([
            'message' => 'Invalid',
        ]);
    }

    /**
     * @test
     */
    public function normalization()
    {
        $constraint = new Required();

        $this->assertSame([
            'data-parsley-required' => 'true',
            'data-parsley-required-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer->reveal()));
    }
}
