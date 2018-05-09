<?php

namespace JBen87\ParsleyBundle\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Length extends Constraint
{
    /**
     * @var int
     */
    private $min;

    /**
     * @var int
     */
    private $max;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->min = $options['min'];
        $this->max = $options['max'];
    }

    /**
     * @inheritdoc
     */
    protected function getAttribute(): string
    {
        return 'data-parsley-length';
    }

    /**
     * @inheritdoc
     */
    protected function getValue(): string
    {
        return sprintf('[%d, %d]', $this->min, $this->max);
    }

    /**
     * @inheritdoc
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(['min', 'max'])
            ->setAllowedTypes('min', ['int'])
            ->setAllowedTypes('max', ['int'])
        ;
    }
}
