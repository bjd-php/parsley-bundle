<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use JBen87\ParsleyBundle\Tests\Constraint\ConstraintTestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class RangeTest extends ConstraintTestCase
{
    /**
     * @inheritdoc
     */
    public function testEmptyConfiguration(): void
    {
        $this->expectException(MissingOptionsException::class);

        new ParsleyAssert\Range();
    }

    public function testIncompleteConfiguration(): void
    {
        $this->expectException(MissingOptionsException::class);

        new ParsleyAssert\Range([
            'min' => 5,
            'maxMessage' => 'Too long',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidOptionsException::class);

        new ParsleyAssert\Range([
            'min' => '5',
            'max' => '10',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function testNormalization(): void
    {
        $constraint = new ParsleyAssert\Range([
            'min' => 5,
            'max' => 10,
        ]);

        $this->assertSame([
            'data-parsley-min' => '5',
            'data-parsley-min-message' => 'Invalid.',
            'data-parsley-max' => '10',
            'data-parsley-max-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));
    }
}
