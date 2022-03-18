<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Constraint\Reader;

use Symfony\Component\Form\FormInterface;

final class FormTypeReader implements ReaderInterface
{
    public function read(FormInterface $form): array
    {
        $config = $form->getConfig();
        if (!$config->hasOption('constraints')) {
            return [];
        }

        return $config->getOption('constraints');
    }

    /**
     * @codeCoverageIgnore
     */
    public function getPriority(): int
    {
        return 0;
    }
}
