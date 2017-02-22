<?php

namespace JBen87\ParsleyBundle\Validator\Constraints;

use JBen87\ParsleyBundle\Validator\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author VKost <viktor.kostadinov@gmail.com>
 */
class GreaterThan extends Constraint
{
    /**
     * @var int
     */
    private $value;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->value = $options['value'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttribute()
    {
        return 'data-parsley-gt';
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['value']);

        if (method_exists($resolver, 'setDefined')) {
            $resolver->setAllowedTypes('value', ['int']);
        } else {
            $resolver->setAllowedTypes([
                'value' => 'int',
            ]);
        }
    }
}
