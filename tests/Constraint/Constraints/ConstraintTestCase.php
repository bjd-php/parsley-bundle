<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

abstract class ConstraintTestCase extends TestCase
{
    /**
     * @var MockObject|NormalizerInterface|null
     */
    protected ?MockObject $normalizer = null;

    abstract public function testNormalization(): void;

    protected function setUp(): void
    {
        $this->normalizer = $this->createMock(NormalizerInterface::class);
    }
}
