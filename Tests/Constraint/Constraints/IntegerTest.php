<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;

class IntegerTest extends ConstraintTestCase
{
    public function testNormalization(): void
    {
        $constraint = new ParsleyAssert\Integer();

        $this->assertSame([
            'data-parsley-type' => 'integer',
            'data-parsley-type-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));
    }
}
