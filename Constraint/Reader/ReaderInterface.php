<?php

namespace JBen87\ParsleyBundle\Constraint\Reader;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

interface ReaderInterface
{
    /**
     * @param FormInterface $form
     *
     * @return SymfonyConstraint[]
     */
    public function read(FormInterface $form): array;

    /**
     * @return int
     */
    public function getPriority(): int;
}
