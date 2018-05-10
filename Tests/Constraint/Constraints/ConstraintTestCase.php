<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

abstract class ConstraintTestCase extends TestCase
{
    /**
     * @var MockObject|NormalizerInterface
     */
    protected $normalizer;

    abstract public function testNormalization(): void;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        $this->normalizer = $this->createMock(NormalizerInterface::class);
    }
}
