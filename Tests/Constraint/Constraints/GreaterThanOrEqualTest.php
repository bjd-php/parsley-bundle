<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class GreaterThanOrEqualTest extends ConfiguredConstraintTestCase
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
            'value' => '5',
        ]);
    }

    public function testNormalization(): void
    {
        $constraint = new ParsleyAssert\GreaterThanOrEqual([
            'value' => 5,
        ]);

        $this->assertSame([
            'data-parsley-gte' => '5',
            'data-parsley-gte-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));
    }
}
