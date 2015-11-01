<?php

namespace JBen87\ParsleyBundle\Validator\Constraints;

use JBen87\ParsleyBundle\Validator\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
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
     * {@inheritdoc}
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->min = $options['min'];
        $this->max = $options['max'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttribute()
    {
        return 'data-parsley-length';
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
        return sprintf('[%d, %d]', $this->min, $this->max);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['min', 'max'])
            ->setAllowedTypes([
                'min' => 'int',
                'max' => 'int',
            ]);
    }
}
