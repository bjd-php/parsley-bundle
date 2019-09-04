<?php

namespace JBen87\ParsleyBundle\Constraint\Factory;

use Symfony\Contracts\Translation\TranslatorInterface;

interface TranslatableFactoryInterface extends FactoryInterface
{
    /**
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator): void;
}
