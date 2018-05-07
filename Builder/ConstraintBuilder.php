<?php

namespace JBen87\ParsleyBundle\Builder;

use JBen87\ParsleyBundle\Exception\Builder\InvalidConfigurationException;
use JBen87\ParsleyBundle\Factory\ConstraintFactory;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;

class ConstraintBuilder implements BuilderInterface
{
    /**
     * @var Constraint[]
     */
    private $constraints;

    /**
     * @var ConstraintFactory
     */
    private $factory;

    /**
     * @param ConstraintFactory $factory
     */
    public function __construct(ConstraintFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @inheritdoc
     */
    public function build(): array
    {
        if (null === $this->constraints) {
            throw new InvalidConfigurationException();
        }

        return array_map(function (Constraint $constraint) {
            return $this->factory->create($constraint);
        }, $this->constraints);
    }

    /**
     * @inheritdoc
     */
    public function configure(array $options): void
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver
            ->setRequired(['constraints'])
            ->setAllowedTypes('constraints', ['array'])
        ;

        $options = $optionsResolver->resolve($options);

        $this->constraints = $options['constraints'];
    }
}
