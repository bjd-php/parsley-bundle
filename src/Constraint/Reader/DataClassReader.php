<?php

declare(strict_types=1);

namespace JBen87\ParsleyBundle\Constraint\Reader;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Exception\NoSuchMetadataException;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class DataClassReader implements ReaderInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function read(FormInterface $form): array
    {
        $config = $form->getRoot()->getConfig();
        if (null === $config->getDataClass()) {
            return [];
        }

        try {
            $metadata = $this->validator->getMetadataFor($config->getDataClass());
        } catch (NoSuchMetadataException $exception) {
            return [];
        }

        if (!$metadata instanceof ClassMetadata) {
            return [];
        }

        $constraints = [];
        foreach ($metadata->getPropertyMetadata($form->getName()) as $propertyMetadatum) {
            $constraints = array_merge($constraints, $propertyMetadatum->findConstraints($metadata->getDefaultGroup()));
        }

        return $constraints;
    }

    /**
     * DataClassReader priority should be greater than FormTypeReader priority
     * so that FormType constraints override entity constraints.
     *
     * @codeCoverageIgnore
     */
    public function getPriority(): int
    {
        return 10;
    }
}
