<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use JBen87\ParsleyBundle\Exception\ConstraintException;

final class TypeTest extends ConstraintTestCase
{
    public function testNormalization(): void
    {
        $constraint = new class extends ParsleyAssert\Type
        {
            protected function getType(): string
            {
                return 'foo';
            }
        };

        $this->expectException(ConstraintException::class);
        $constraint->normalize($this->normalizer);
    }
}
