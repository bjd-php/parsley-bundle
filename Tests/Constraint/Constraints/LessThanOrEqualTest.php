<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class LessThanOrEqualTest extends ConfiguredConstraintTestCase
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
            'value' => '10',
        ]);
    }

    public function testNormalization(): void
    {
        $constraint = new ParsleyAssert\LessThanOrEqual([
            'value' => 10,
        ]);

        $this->assertSame([
            'data-parsley-lte' => '10',
            'data-parsley-lte-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));
    }
}
