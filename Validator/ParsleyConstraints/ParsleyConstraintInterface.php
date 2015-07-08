<?php

namespace ParsleyBundle\Validator\ParsleyConstraints;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
interface ParsleyConstraintInterface
{
    /**
     * @return array
     */
    public function toArray();

    /**
     * @return string
     */
    public function render();
}
