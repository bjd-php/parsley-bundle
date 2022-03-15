<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

final class PatternTest extends ConfiguredConstraintTestCase
{
    public function testEmptyConfiguration(): void
    {
        $this->expectException(MissingOptionsException::class);

        new ParsleyAssert\Pattern();
    }

    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidOptionsException::class);

        new ParsleyAssert\Pattern([
            'pattern' => false,
        ]);
    }

    public function testNormalization(): void
    {
        $constraint = new ParsleyAssert\Pattern([
            'pattern' => '\w',
        ]);

        $this->assertSame([
            'data-parsley-pattern' => '\w',
            'data-parsley-pattern-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));
    }
}
