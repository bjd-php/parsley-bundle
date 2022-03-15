<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

final class MinTest extends ConfiguredConstraintTestCase
{
    public function testEmptyConfiguration(): void
    {
        $this->expectException(MissingOptionsException::class);

        new ParsleyAssert\Min();
    }

    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidOptionsException::class);

        new ParsleyAssert\Min([
            'min' => '5',
        ]);
    }

    public function testNormalization(): void
    {
        $constraint = new ParsleyAssert\Min([
            'min' => 5,
        ]);

        $this->assertSame([
            'data-parsley-min' => '5',
            'data-parsley-min-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));
    }
}
