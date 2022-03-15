<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

final class MaxLengthTest extends ConfiguredConstraintTestCase
{
    public function testEmptyConfiguration(): void
    {
        $this->expectException(MissingOptionsException::class);

        new ParsleyAssert\MaxLength();
    }

    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidOptionsException::class);

        new ParsleyAssert\MaxLength([
            'max' => '10',
        ]);
    }

    public function testNormalization(): void
    {
        $constraint = new ParsleyAssert\MaxLength([
            'max' => 10,
        ]);

        $this->assertSame([
            'data-parsley-maxlength' => '10',
            'data-parsley-maxlength-message' => 'Invalid.',
            'maxlength' => '10',
        ], $constraint->normalize($this->normalizer));
    }
}
