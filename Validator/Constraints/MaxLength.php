<?php

namespace JBen87\ParsleyBundle\Validator\Constraints;

use JBen87\ParsleyBundle\Validator\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class MaxLength extends Constraint
{
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

        $this->max = $options['max'];
    }

    /**
     * {@inheritdoc}
     */
    public function normalize(NormalizerInterface $normalizer, $format = null, array $context = [])
    {
        return parent::normalize($normalizer, $format, $context) + ['maxlength' => $this->getValue()];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttribute()
    {
        return 'data-parsley-maxlength';
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
        return $this->max;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['max']);

        if (method_exists($resolver, 'setDefined')) {
            $resolver->setAllowedTypes('max', ['int']);
        } else {
            $resolver->setAllowedTypes([
                'max' => 'int',
            ]);
        }
    }
}
