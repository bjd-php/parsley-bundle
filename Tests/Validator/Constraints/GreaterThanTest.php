<?php

namespace Tests\Unit\Validator\Constraints;

use JBen87\ParsleyBundle\Tests\Validator\Constraint;
use JBen87\ParsleyBundle\Validator\Constraints\GreaterThan;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class GreaterThanTest extends Constraint
{
    /**
     * @inheritdoc
     */
    public function testEmptyConfiguration(): void
    {
        $this->expectException(MissingOptionsException::class);

        new GreaterThan();
    }

    /**
     * @inheritdoc
     */
    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidOptionsException::class);

        new GreaterThan([
            'value' => '5',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function testNormalization(): void
    {
        $constraint = new GreaterThan([
            'value' => 5,
        ]);

        $this->assertSame([
            'data-parsley-gt' => '5',
            'data-parsley-gt-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer->reveal()));
    }
}
