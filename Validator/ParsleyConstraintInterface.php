<?php

namespace JBen87\ParsleyBundle\Validator;

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
