<?php

namespace JBen87\ParsleyBundle\Constraint\Factory;

use Symfony\Contracts\Translation\TranslatorInterface;

interface TranslatableFactoryInterface extends FactoryInterface
{
    public function setTranslator(TranslatorInterface $translator): void;
}
