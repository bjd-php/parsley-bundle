<?php

namespace JBen87\ParsleyBundle\Tests\Validator\Constraints;

use JBen87\ParsleyBundle\Tests\Validator\Constraint;
use JBen87\ParsleyBundle\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class LengthTest extends Constraint
{
    /**
     * @inheritdoc
     */
    public function testEmptyConfiguration(): void
    {
        $this->expectException(MissingOptionsException::class);

        new Length();
    }

    public function testIncompleteConfiguration(): void
    {
        $this->expectException(MissingOptionsException::class);

        new Length([
            'min' => 5,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidOptionsException::class);

        new Length([
            'min' => '5',
            'max' => '10',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function testNormalization(): void
    {
        $constraint = new Length([
            'min' => 5,
            'max' => 10,
        ]);

        $this->assertSame([
            'data-parsley-length' => '[5, 10]',
            'data-parsley-length-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer->reveal()));
    }
}
