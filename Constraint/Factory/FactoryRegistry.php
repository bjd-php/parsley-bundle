<?php

namespace JBen87\ParsleyBundle\Constraint\Factory;

use JBen87\ParsleyBundle\Exception\ConstraintException;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class FactoryRegistry
{
    /**
     * @var FactoryInterface[]|iterable
     */
    private $factories;

    /**
     * @param FactoryInterface[]|iterable $factories
     */
    public function __construct(iterable $factories)
    {
        $this->factories = $factories;
    }

    /**
     * @param SymfonyConstraint $constraint
     *
     * @return FactoryInterface
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
