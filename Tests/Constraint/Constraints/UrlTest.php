<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;

final class UrlTest extends ConstraintTestCase
{
    public function testNormalization(): void
    {
        $constraint = new ParsleyAssert\Url();

        $this->assertSame([
            'data-parsley-type' => 'url',
            'data-parsley-type-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));
    }
}
