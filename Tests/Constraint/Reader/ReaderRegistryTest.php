<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Reader;

use JBen87\ParsleyBundle\Constraint\Reader\ReaderInterface;
use JBen87\ParsleyBundle\Constraint\Reader\ReaderRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;

final class ReaderRegistryTest extends TestCase
{
    public function testAll()
    {
        $reader1 = new class implements ReaderInterface
        {
            public function read(FormInterface $form): array
            {
                return [];
            }

            public function getPriority(): int
            {
                return 10;
            }
        };

        $reader2 = new class implements ReaderInterface
        {
            public function read(FormInterface $form): array
            {
                return [];
            }

            public function getPriority(): int
            {
                return 0;
            }
        };

        $registry = new ReaderRegistry([$reader1, $reader2]);

        $this->assertSame([$reader2, $reader1], $registry->all());
    }
}
