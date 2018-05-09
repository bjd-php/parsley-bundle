<?php

namespace JBen87\ParsleyBundle\Tests\Constraint;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

abstract class ConstraintTestCase extends TestCase
{
    /**
     * @var MockObject|NormalizerInterface
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
        $this->normalizer = $this->createMock(NormalizerInterface::class);
    }
}
