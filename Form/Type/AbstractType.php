<?php

namespace ParsleyBundle\Form\Type;

use ParsleyBundle\Form\Adapter\ConstraintsAdapter;
use Symfony\Component\Form\AbstractType as BaseType;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
abstract class AbstractType extends BaseType
{
    /**
     * @param ConstraintsAdapter $constraintsAdapter
     */
    public function __construct(ConstraintsAdapter $constraintsAdapter)
    {
        $this->constraintsAdapter = $constraintsAdapter;
    }

    /**
     * @param array $constraints
     *
     * @return array
     */
    protected function generateConstraints(array $constraints)
    {
        $symfonyConstraints = $constraints;
        $parsleyConstraints = [];

        foreach ($constraints as $field => $items) {
            $parsleyConstraints[$field] = $this->constraintsAdapter->generateConstraints($items);
        }

        return [
            'symfony'   => $symfonyConstraints,
            'parsley'   => $parsleyConstraints
        ];
    }
}
