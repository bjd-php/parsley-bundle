<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Constraint\Reader;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

interface ReaderInterface
{
    /**
     * @return SymfonyConstraint[]
     */
    public function read(FormInterface $form): array;

    public function getPriority(): int;
}
