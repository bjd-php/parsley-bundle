<?php

namespace JBen87\ParsleyBundle\Tests\Resources\Form\Type;

use JBen87\ParsleyBundle\Form\Adapter\ConstraintsAdapter;
use JBen87\ParsleyBundle\Form\Type\AbstractType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class CustomType extends AbstractType
{
    /**
     * @var array
     */
    protected $constraints;

    /**
     * @param ConstraintsAdapter $constraintsAdapter
     */
    public function __construct(ConstraintsAdapter $constraintsAdapter)
    {
        parent::__construct($constraintsAdapter);

        $this->constraints = $this->generateConstraints($this->getSymfonyConstraints());
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'custom';
    }

    /**
     * @return array
     */
    public function getSymfonyConstraints()
    {
        return [
            'field' => [
                new Constraints\NotBlank(),
                new Constraints\Email()
            ]
        ];
    }

    /**
     * @return array
     */
    public function getConstraints()
    {
        return $this->constraints;
    }
}
