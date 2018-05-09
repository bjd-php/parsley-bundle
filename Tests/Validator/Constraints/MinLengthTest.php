<?php

namespace JBen87\ParsleyBundle\Tests\Validator\Constraints;

use JBen87\ParsleyBundle\Tests\Validator\Constraint;
use JBen87\ParsleyBundle\Validator\Constraints\MinLength;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class MinLengthTest extends Constraint
{
    /**
     * @inheritdoc
     */
    public function testEmptyConfiguration(): void
    {
        $this->expectException(MissingOptionsException::class);

        new MinLength();
    }

    /**
     * @inheritdoc
     */
    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidOptionsException::class);

        new MinLength([
            'min' => '5',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function testNormalization(): void
    {
        $constraint = new MinLength([
            'min' => 5,
        ]);

        $this->assertSame([
            'data-parsley-minlength' => '5',
            'data-parsley-minlength-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer->reveal()));
    }
}
