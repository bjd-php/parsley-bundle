<?php

namespace JBen87\ParsleyBundle\Validator\ConstraintsReader;

use Symfony\Component\Form\FormInterface;

class FormConstraintsReader implements ConstraintsReaderInterface
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
        return 10;
    }
}
