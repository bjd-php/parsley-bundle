<?php

namespace JBen87\ParsleyBundle\Factory;

use JBen87\ParsleyBundle\Exception\Validator\ConstraintException;
use JBen87\ParsleyBundle\Validator\Constraint as ParsleyConstraint;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ChainFactory implements FactoryInterface
{
    /**
     * @var FactoryInterface[]
     */
    private $factories;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param FactoryInterface[] $factories
     * @param TranslatorInterface $translator
     */
    public function __construct(array $factories, TranslatorInterface $translator)
    {
        $this->factories = $factories;
        $this->translator = $translator;
    }

    /**
     * @inheritdoc
     */
    public function create(SymfonyConstraint $constraint): ParsleyConstraint
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
