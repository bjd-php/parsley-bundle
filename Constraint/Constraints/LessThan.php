<?php

namespace JBen87\ParsleyBundle\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessThan extends Constraint
{
    /**
     * @var int
     */
    private $value;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->value = $options['value'];
    }

    /**
     * @inheritdoc
     */
    protected function getAttribute(): string
    {
        return 'data-parsley-lt';
    }

    /**
     * @inheritdoc
     */
    protected function getValue(): string
    {
        return (string) $this->value;
    }

    /**
     * @inheritdoc
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(['value'])
            ->setAllowedTypes('value', ['numeric'])
        ;
    }
}
