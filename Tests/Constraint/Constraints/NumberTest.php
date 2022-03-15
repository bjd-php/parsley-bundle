<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;

final class NumberTest extends ConstraintTestCase
{
    public function testNormalization(): void
    {
        $constraint = new ParsleyAssert\Number();

        $this->assertSame([
            'data-parsley-type' => 'number',
            'data-parsley-type-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));
    }
}
