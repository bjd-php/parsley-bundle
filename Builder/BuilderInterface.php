<?php

namespace JBen87\ParsleyBundle\Builder;

use JBen87\ParsleyBundle\Exception\Builder\InvalidConfigurationException;
use JBen87\ParsleyBundle\Validator\Constraint;

interface BuilderInterface
{
    /**
     * @return Constraint[]
     * @throws InvalidConfigurationException
     */
    public function build(): array;

    /**
     * @param array $options
     */
    public function configure(array $options): void;
}
