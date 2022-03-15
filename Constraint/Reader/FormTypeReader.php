<?php

namespace JBen87\ParsleyBundle\Constraint\Reader;

use Symfony\Component\Form\FormInterface;

final class FormTypeReader implements ReaderInterface
{
    /**
     * @inheritdoc
     */
    public function read(FormInterface $form): array
    {
        $config = $form->getConfig();
        if (false === $config->hasOption('constraints')) {
            return [];
        }

        return $config->getOption('constraints');
    }

    /**
     * @inheritdoc
     *
     * @codeCoverageIgnore
     */
    public function getPriority(): int
    {
        return 0;
    }
}
