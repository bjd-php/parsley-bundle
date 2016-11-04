<?php

namespace JBen87\ParsleyBundle\Builder;

use JBen87\ParsleyBundle\Exception\Builder\InvalidConfigurationException;
use JBen87\ParsleyBundle\Exception\Validator\UnsupportedConstraintException;
use JBen87\ParsleyBundle\Factory\ConstraintFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ConstraintFactory $factory
     */
    public function __construct(ConstraintFactory $factory, LoggerInterface $logger = null)
    {
        $this->factory = $factory;
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        if (null === $this->constraints) {
            throw new InvalidConfigurationException();
        }

        $constraints = [];

        foreach ($this->constraints as $constraint) {
            try {
                $constraints[] = $this->factory->create($constraint);
            } catch (UnsupportedConstraintException $e) {
                $this->logger->debug('Unsupported Constraint', ['constraint' => $constraint]);
            }
        }

        return $constraints;
    }

    /**
     * {@inheritdoc}
     */
    public function configure(array $options)
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setRequired(['constraints']);

        if (method_exists($optionsResolver, 'setDefined')) {
            $optionsResolver->setAllowedTypes('constraints', ['array']);
        } else {
            $optionsResolver->setAllowedTypes(['constraints' => 'array']);
        }

        $options = $optionsResolver->resolve($options);

        $this->constraints = $options['constraints'];
    }
}
