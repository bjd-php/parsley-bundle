<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class MinLengthTest extends ConfiguredConstraintTestCase
{
    public function testEmptyConfiguration(): void
    {
        $this->expectException(MissingOptionsException::class);

        new ParsleyAssert\MinLength();
    }

    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidOptionsException::class);

        new ParsleyAssert\MinLength([
            'min' => '5',
        ]);
    }

    public function testNormalization(): void
    {
        $constraint = new ParsleyAssert\MinLength([
            'min' => 5,
        ]);

        $this->assertSame([
            'data-parsley-minlength' => '5',
            'data-parsley-minlength-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));
    }
}
