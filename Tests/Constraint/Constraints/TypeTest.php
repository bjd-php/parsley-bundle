<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use JBen87\ParsleyBundle\Exception\ConstraintException;
use JBen87\ParsleyBundle\Tests\Constraint\ConstraintTestCase;

class TypeTest extends ConstraintTestCase
{
    /**
     * @inheritdoc
     */
    public function testEmptyConfiguration(): void
    {
        $this->markTestSkipped();
    }

    /**
     * @inheritdoc
     */
    public function testInvalidConfiguration(): void
    {
        $this->markTestSkipped();
    }

    /**
     * @inheritdoc
     */
    public function testNormalization(): void
    {
        $this->markTestSkipped();
    }

    public function testNormalizationInvalidValue(): void
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
