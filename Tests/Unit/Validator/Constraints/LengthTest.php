<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator\Constraints;

use JBen87\ParsleyBundle\Tests\Unit\Validator\Constraint;
use JBen87\ParsleyBundle\Validator\Constraints\Length;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class LengthTest extends Constraint
{
    /**
     * {@inheritdoc}
     *
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function emptyConfiguration()
    {
        new Length();
    }

    /**
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function incompleteConfiguration()
    {
        new Length([
            'min' => 5,
        ]);
    }

    /**
     * {@inheritdoc}
     *
     * @test
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function invalidConfiguration()
    {
        new Length([
            'min' => '5',
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
        new Length([
            'min' => 5,
            'max' => 10,
        ]);

        new Length([
            'min' => 5,
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
        $constraint = new Length([
            'min' => 5,
            'max' => 10,
        ]);

        $this->assertSame([
            'data-parsley-length' => '[5, 10]',
            'data-parsley-length-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer->reveal()));
    }
}
