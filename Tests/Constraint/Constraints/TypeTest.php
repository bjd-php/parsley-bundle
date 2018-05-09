<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use JBen87\ParsleyBundle\Exception\ConstraintException;

class TypeTest extends ConstraintTestCase
{
    public function testNormalization(): void
    {
        $constraint = new class extends ParsleyAssert\Type
        {
            /**
             * @inheritdoc
             */
            protected function getType(): string
            {
                return 'foo';
            }
        };

        $this->expectException(ConstraintException::class);
        $constraint->normalize($this->normalizer);
    }
}
