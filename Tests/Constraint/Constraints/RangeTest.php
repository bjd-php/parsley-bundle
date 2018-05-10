<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class RangeTest extends ConfiguredConstraintTestCase
{
    public function testEmptyConfiguration(): void
    {
        $this->expectException(MissingOptionsException::class);

        new ParsleyAssert\Range();
    }

    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidOptionsException::class);

        new ParsleyAssert\Range([
            'min' => '5',
            'max' => '10',
        ]);
    }

    public function testNormalization(): void
    {
        $constraint = new ParsleyAssert\Range([
            'min' => 5,
            'max' => 10,
        ]);

        $this->assertSame([
            'data-parsley-min' => '5',
            'data-parsley-min-message' => 'Invalid.',
            'data-parsley-max' => '10',
            'data-parsley-max-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));
    }
}
