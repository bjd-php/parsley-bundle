<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator\Constraints;

use JBen87\ParsleyBundle\Tests\Unit\Validator\Constraint;
use JBen87\ParsleyBundle\Validator\Constraints\Type;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class TypeTest extends Constraint
{
    /**
     * {@inheritdoc}
     *
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function emptyConfiguration()
    {
        new Type();
    }

    /**
     * {@inheritdoc}
     *
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function invalidConfiguration()
    {
        new Type([
            'type' => 'string',
        ]);
    }

    /**
     * {@inheritdoc}
     *
     * @test
     */
    public function validConfiguration()
    {
        new Type([
            'type' => 'email',
        ]);

        new Type([
            'type' => 'email',
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
        $constraint = new Type([
            'type' => 'email',
        ]);

        $this->assertSame([
            'data-parsley-type' => 'email',
            'data-parsley-type-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer->reveal()));
    }
}
