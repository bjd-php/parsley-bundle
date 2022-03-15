<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;

final class EmailTest extends ConstraintTestCase
{
    public function testNormalization(): void
    {
        $constraint = new ParsleyAssert\Email();

        $this->assertSame([
            'data-parsley-type' => 'email',
            'data-parsley-type-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));
    }
}
