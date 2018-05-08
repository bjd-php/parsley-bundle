<?php

namespace JBen87\ParsleyBundle\Factory;

use Symfony\Component\Translation\TranslatorInterface;

interface TranslatableFactoryInterface extends FactoryInterface
{
    /**
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator): void;
}
