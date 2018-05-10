<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraints as ParsleyAssert;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class LengthTest extends ConfiguredConstraintTestCase
{
    public function testEmptyConfiguration(): void
    {
        $this->expectException(MissingOptionsException::class);

        new ParsleyAssert\Length();
    }

    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidOptionsException::class);

        new ParsleyAssert\Length([
            'min' => '5',
            'max' => '10',
        ]);
    }

    public function testNormalization(): void
    {
        $constraint = new ParsleyAssert\Length([
            'min' => 5,
            'max' => 10,
        ]);

        $this->assertSame([
            'data-parsley-length' => '[5, 10]',
            'data-parsley-length-message' => 'Invalid.',
        ], $constraint->normalize($this->normalizer));
    }
}
