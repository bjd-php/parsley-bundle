<?php

namespace JBen87\ParsleyBundle\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class Pattern extends Constraint
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->pattern = $options['pattern'];
    }

    /**
     * @inheritdoc
     */
    protected function getAttribute(): string
    {
        return 'data-parsley-pattern';
    }

    /**
     * @inheritdoc
     */
    protected function getValue(): string
    {
        return $this->pattern;
    }

    /**
     * @inheritdoc
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(['pattern'])
            ->setAllowedTypes('pattern', ['string'])
        ;
    }
}
