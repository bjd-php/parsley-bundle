<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;

final class RequiredTest extends ConstraintTestCase
{
    public function testNormalization(): void
    {
        $constraint = new ParsleyAssert\Required();

        $this->assertSame([
            'data-parsley-required' => 'true',
            'data-parsley-required-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));
    }
}
