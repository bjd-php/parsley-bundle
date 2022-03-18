<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

final class LessThanOrEqualTest extends ConfiguredConstraintTestCase
{
    public function testEmptyConfiguration(): void
    {
        $this->expectException(MissingOptionsException::class);

        new ParsleyAssert\LessThanOrEqual();
    }

    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidOptionsException::class);

        new ParsleyAssert\LessThanOrEqual([
            'value' => 'foo',
        ]);
    }

    public function testNormalization(): void
    {
        // integer
        $constraint = new ParsleyAssert\LessThanOrEqual([
            'value' => 10,
        ]);

        $this->assertSame([
            'data-parsley-lte' => '10',
            'data-parsley-lte-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));

        // float to int
        $constraint = new ParsleyAssert\LessThanOrEqual([
            'value' => 10.0,
        ]);

        $this->assertSame([
            'data-parsley-lte' => '10',
            'data-parsley-lte-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));

        // floating
        $constraint = new ParsleyAssert\LessThanOrEqual([
            'value' => 10.3,
        ]);

        $this->assertSame([
            'data-parsley-lte' => '10.3',
            'data-parsley-lte-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));

        // string
        $constraint = new ParsleyAssert\LessThanOrEqual([
            'value' => '10',
        ]);

        $this->assertSame([
            'data-parsley-lte' => '10',
            'data-parsley-lte-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));
    }
}
