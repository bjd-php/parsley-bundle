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
    private $greater_than;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->greater_than = $options['greater_than'];
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
        return $this->greater_than;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['greater_than']);

        if (method_exists($resolver, 'setDefined')) {
            $resolver->setAllowedTypes('greater_than', ['int']);
        } else {
            $resolver->setAllowedTypes([
                'greater_than' => 'int',
            ]);
        }
    }
}
