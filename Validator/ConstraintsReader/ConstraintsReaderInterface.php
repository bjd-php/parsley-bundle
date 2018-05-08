<?php

namespace JBen87\ParsleyBundle\Validator\ConstraintsReader;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;

interface ConstraintsReaderInterface
{
    /**
     * @param FormInterface $form
     *
     * @return Constraint[]
     */
    public function read(FormInterface $form): array;

    /**
     * @return int
     */
    public function getPriority(): int;
}
