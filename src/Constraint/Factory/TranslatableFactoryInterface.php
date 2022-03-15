<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Constraint\Factory;

use Symfony\Contracts\Translation\TranslatorInterface;

interface TranslatableFactoryInterface extends FactoryInterface
{
    public function setTranslator(TranslatorInterface $translator): void;
}
