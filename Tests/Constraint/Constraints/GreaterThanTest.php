<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class GreaterThanTest extends ConfiguredConstraintTestCase
{
    public function testEmptyConfiguration(): void
    {
        $this->expectException(MissingOptionsException::class);

        new ParsleyAssert\GreaterThan();
    }

    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidOptionsException::class);

        new ParsleyAssert\GreaterThan([
            'value' => 'foo',
        ]);
    }

    public function testNormalization(): void
    {
        // integer
        $constraint = new ParsleyAssert\GreaterThan([
            'value' => 5,
        ]);

        $this->assertSame([
            'data-parsley-gt' => '5',
            'data-parsley-gt-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));

        // float to int
        $constraint = new ParsleyAssert\GreaterThan([
            'value' => 5.0,
        ]);

        $this->assertSame([
            'data-parsley-gt' => '5',
            'data-parsley-gt-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));

        // floating
        $constraint = new ParsleyAssert\GreaterThan([
            'value' => 5.2,
        ]);

        $this->assertSame([
            'data-parsley-gt' => '5.2',
            'data-parsley-gt-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));

        // string
        $constraint = new ParsleyAssert\GreaterThan([
            'value' => '5',
        ]);

        $this->assertSame([
            'data-parsley-gt' => '5',
            'data-parsley-gt-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));
    }
}
