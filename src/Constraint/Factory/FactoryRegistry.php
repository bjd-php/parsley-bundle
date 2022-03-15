<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Constraint\Factory;

use JBen87\ParsleyBundle\Exception\ConstraintException;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

final class FactoryRegistry
{
    /**
     * @var FactoryInterface[]
     */
    private iterable $factories;

    /**
     * @param FactoryInterface[] $factories
     */
    public function __construct(iterable $factories)
    {
        $this->factories = $factories;
    }

    /**
     * @throws ConstraintException
     */
    public function findForConstraint(SymfonyConstraint $constraint): FactoryInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($constraint)) {
                return $factory;
            }
        }

        throw ConstraintException::createUnsupportedException();
    }
}
