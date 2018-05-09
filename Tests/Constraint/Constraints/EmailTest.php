<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use JBen87\ParsleyBundle\Tests\Constraint\ConstraintTestCase;

class EmailTest extends ConstraintTestCase
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
        $constraint = new ParsleyAssert\Email();

        $this->assertSame([
            'data-parsley-type' => 'email',
            'data-parsley-type-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));
    }
}
