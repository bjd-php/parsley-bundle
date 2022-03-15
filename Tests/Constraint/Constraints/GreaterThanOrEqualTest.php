<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

final class GreaterThanOrEqualTest extends ConfiguredConstraintTestCase
{
    public function testEmptyConfiguration(): void
    {
        $this->expectException(MissingOptionsException::class);

        new ParsleyAssert\GreaterThanOrEqual();
    }

    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidOptionsException::class);

        new ParsleyAssert\GreaterThanOrEqual([
            'value' => 'foo',
        ]);
    }

    public function testNormalization(): void
    {
        // integer
        $constraint = new ParsleyAssert\GreaterThanOrEqual([
            'value' => 5,
        ]);

        $this->assertSame([
            'data-parsley-gte' => '5',
            'data-parsley-gte-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));

        // float to int
        $constraint = new ParsleyAssert\GreaterThanOrEqual([
            'value' => 5.0,
        ]);

        $this->assertSame([
            'data-parsley-gte' => '5',
            'data-parsley-gte-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));

        // floating
        $constraint = new ParsleyAssert\GreaterThanOrEqual([
            'value' => 5.2,
        ]);

        $this->assertSame([
            'data-parsley-gte' => '5.2',
            'data-parsley-gte-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));

        // string
        $constraint = new ParsleyAssert\GreaterThanOrEqual([
            'value' => '5',
        ]);

        $this->assertSame([
            'data-parsley-gte' => '5',
            'data-parsley-gte-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));
    }
}
