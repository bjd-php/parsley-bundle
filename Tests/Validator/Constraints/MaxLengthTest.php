<?php

namespace JBen87\ParsleyBundle\Tests\Validator\Constraints;

use JBen87\ParsleyBundle\Tests\Validator\Constraint;
use JBen87\ParsleyBundle\Validator\Constraints\MaxLength;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class MaxLengthTest extends Constraint
{
    /**
     * @inheritdoc
     */
    public function testEmptyConfiguration(): void
    {
        $this->expectException(MissingOptionsException::class);

        new MaxLength();
    }

    /**
     * @inheritdoc
     */
    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidOptionsException::class);

        new MaxLength([
            'max' => '10',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function testNormalization(): void
    {
        $constraint = new MaxLength([
            'max' => 10,
        ]);

        $this->assertSame([
            'data-parsley-maxlength' => '10',
            'data-parsley-maxlength-message' => 'Invalid.',
            'maxlength' => '10',
        ], $constraint->normalize($this->normalizer->reveal()));
    }
}
