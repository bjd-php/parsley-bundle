<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

abstract class Constraint extends TestCase
{
    /**
     * @var ObjectProphecy|NormalizerInterface
     */
    protected $normalizer;

    /**
     * Test creating the constraint without options.
     */
    abstract public function testEmptyConfiguration(): void;

    /**
     * Test creating the constraint with invalid options.
     */
    abstract public function testInvalidConfiguration(): void;

    /**
     * Test normalizing the constraint.
     */
    abstract public function testNormalization(): void;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        $this->normalizer = $this->prophesize(NormalizerInterface::class);
    }
}
