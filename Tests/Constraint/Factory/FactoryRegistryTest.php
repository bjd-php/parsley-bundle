<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Factory\FactoryInterface;
use JBen87\ParsleyBundle\Constraint\Factory\FactoryRegistry;
use JBen87\ParsleyBundle\Constraint\Factory\RequiredFactory;
use JBen87\ParsleyBundle\Exception\ConstraintException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints as Assert;

class FactoryRegistryTest extends TestCase
{
    public function testFindForConstraint(): void
    {
        $registry = $this->createRegistry([
            new RequiredFactory(),
        ]);

        $factory = $registry->findForConstraint(new Assert\NotBlank());
        $this->assertInstanceOf(RequiredFactory::class, $factory);
    }

    public function testFindForConstraintUnsupported(): void
    {
        $this->expectException(ConstraintException::class);
        $this->createRegistry([])->findForConstraint(new Assert\NotBlank());
    }

    /**
     * @param FactoryInterface[] $factories
     *
     * @return FactoryRegistry
     */
    private function createRegistry(array $factories): FactoryRegistry
    {
        return new FactoryRegistry($factories);
    }
}
