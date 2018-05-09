<?php

namespace Tests\Unit\Validator\Constraints;

use JBen87\ParsleyBundle\Tests\Validator\ConstraintTestCase;
use JBen87\ParsleyBundle\Validator\Constraints as ParsleyAssert;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class GreaterThanTest extends ConstraintTestCase
{
    /**
     * @inheritdoc
     */
    public function testEmptyConfiguration(): void
    {
        $this->expectException(MissingOptionsException::class);

        new ParsleyAssert\GreaterThan();
    }

    /**
     * @inheritdoc
     */
    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidOptionsException::class);

        new ParsleyAssert\GreaterThan([
            'value' => '5',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function testNormalization(): void
    {
        $constraint = new ParsleyAssert\GreaterThan([
            'value' => 5,
        ]);

        $this->assertSame([
            'data-parsley-gt' => '5',
            'data-parsley-gt-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));
    }
}
