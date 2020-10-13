<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class LessThanTest extends ConfiguredConstraintTestCase
{
    public function testEmptyConfiguration(): void
    {
        $this->expectException(MissingOptionsException::class);

        new ParsleyAssert\LessThan();
    }

    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidOptionsException::class);

        new ParsleyAssert\LessThan([
            'value' => [],
        ]);
    }

    public function testNormalization(): void
    {
        // integer
        $constraint = new ParsleyAssert\LessThan([
            'value' => 10,
        ]);

        $this->assertSame([
            'data-parsley-lt' => '10',
            'data-parsley-lt-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));

        // float to int
        $constraint = new ParsleyAssert\LessThan([
            'value' => 10.0,
        ]);

        $this->assertSame([
            'data-parsley-lt' => '10',
            'data-parsley-lt-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));

        // floating
        $constraint = new ParsleyAssert\LessThan([
            'value' => 10.5,
        ]);

        $this->assertSame([
            'data-parsley-lt' => '10.5',
            'data-parsley-lt-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));

        // string
        $constraint = new ParsleyAssert\LessThan([
            'value' => "10",
        ]);

        $this->assertSame([
            'data-parsley-lt' => '10',
            'data-parsley-lt-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));
    }
}
