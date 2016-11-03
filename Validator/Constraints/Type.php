<?php

namespace JBen87\ParsleyBundle\Validator\Constraints;

use JBen87\ParsleyBundle\Validator\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class Type extends Constraint
{
    /**
     * @var string
     */
    private $type;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->type = $options['type'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttribute()
    {
        return 'data-parsley-type';
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['type']);

        if (method_exists($resolver, 'setDefined')) {
            $resolver->setAllowedTypes('type', ['string']);
        } else {
            $resolver->setAllowedTypes([
                'type' => 'string',
            ]);
        }

        if (method_exists($resolver, 'setDefined')) {
            $resolver->setAllowedValues('type', ['email', 'number', 'integer', 'digits', 'alphanum', 'url']);
        } else {
            $resolver->setAllowedValues([
                'type' => ['email', 'number', 'integer', 'digits', 'alphanum', 'url'],
            ]);
        }
    }
}
