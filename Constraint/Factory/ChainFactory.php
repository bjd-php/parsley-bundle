<?php

namespace JBen87\ParsleyBundle\Constraint\Factory;

use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Exception\ConstraintException;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ChainFactory implements FactoryInterface
{
    /**
     * @var FactoryInterface[]
     */
    private $factories;

    /**
     * @param FactoryInterface[] $factories
     */
    public function __construct(array $factories)
    {
        $this->factories = $factories;
    }

    /**
     * @inheritdoc
     */
    public function create(SymfonyConstraint $constraint): Constraint
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($constraint)) {
                return $factory->create($constraint);
            }
        }

        throw ConstraintException::createUnsupportedException();
    }

    /**
     * @inheritdoc
     */
    public function supports(SymfonyConstraint $constraint): bool
    {
        return true;
    }
}
