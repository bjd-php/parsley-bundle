<?php

namespace JBen87\ParsleyBundle\Validator\ConstraintsReader;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Exception\NoSuchMetadataException;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EntityConstraintsReader implements ConstraintsReaderInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @inheritdoc
     */
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
     * @inheritdoc
     *
     * @codeCoverageIgnore
     */
    public function getPriority(): int
    {
        return 0;
    }
}
