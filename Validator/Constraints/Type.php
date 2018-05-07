<?php

namespace JBen87\ParsleyBundle\Validator\Constraints;

use JBen87\ParsleyBundle\Validator\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Type extends Constraint
{
    /**
     * @var string
     */
    private $type;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->type = $options['type'];
    }

    /**
     * @inheritdoc
     */
    protected function getAttribute(): string
    {
        return 'data-parsley-type';
    }

    /**
     * @inheritdoc
     */
    protected function getValue(): string
    {
        return (string) $this->type;
    }

    /**
     * @inheritdoc
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(['type'])
            ->setAllowedTypes('type', ['string'])
            ->setAllowedValues('type', ['email', 'number', 'integer', 'digits', 'alphanum', 'url'])
        ;
    }
}
