<?php

namespace JBen87\ParsleyBundle\Builder;

use JBen87\ParsleyBundle\Exception\Builder\InvalidConfigurationException;
use JBen87\ParsleyBundle\Validator\ConstraintFactory;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class ConstraintBuilder
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
     * {@inheritdoc}
     */
    public function build()
    {
        if (null === $this->constraints) {
            throw new InvalidConfigurationException();
        }

        return array_map(function (Constraint $constraint) {
            return $this->factory->create($constraint);
        }, $this->constraints);
    }

    /**
     * {@inheritdoc}
     */
    public function configure(array $options)
    {
        $options = (new OptionsResolver)
            ->setRequired('constraints')
            ->setAllowedTypes('constraints', 'array')
            ->resolve($options);

        $this->constraints = $options['constraints'];
    }
}
