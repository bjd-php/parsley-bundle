<?php

namespace JBen87\ParsleyBundle\Validator\Constraints;

use JBen87\ParsleyBundle\Validator\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class Pattern extends Constraint
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->pattern = $options['pattern'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttribute()
    {
        return 'data-parsley-pattern';
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
        return $this->pattern;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(['pattern'])
            ->setAllowedTypes([
                'pattern' => 'string',
            ]);
    }
}
