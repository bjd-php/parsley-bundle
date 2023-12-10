<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Constraint\Factory;

use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Contracts\Translation\TranslatorInterface;

trait FactoryTrait
{
    private TranslatorInterface $translator;

    #[Required]
    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }

    private function trans(
        string $id,
        array $parameters = [],
        string $domain = 'validators',
        string $locale = null
    ): string {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    private function transChoice(
        string $id,
        int $number,
        array $parameters = [],
        string $domain = 'validators',
        string $locale = null
    ): string {
        $parameters['%count%'] = $number;

        return $this->translator->trans($id, $parameters, $domain, $locale);
    }
}
