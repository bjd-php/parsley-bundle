<?php

namespace JBen87\ParsleyBundle\Validator\Constraints;

use JBen87\ParsleyBundle\Validator\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Max extends Constraint
{
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

        $this->max = $options['max'];
    }

    /**
     * @inheritdoc
     */
    protected function getAttribute(): string
    {
        return 'data-parsley-max';
    }

    /**
     * @inheritdoc
     */
    protected function getValue(): string
    {
        return (string) $this->max;
    }

    /**
     * @inheritdoc
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(['max'])
            ->setAllowedTypes('max', ['int'])
        ;
    }
}
