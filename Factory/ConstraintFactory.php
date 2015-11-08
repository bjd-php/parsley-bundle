<?php

namespace JBen87\ParsleyBundle\Factory;

use JBen87\ParsleyBundle\Exception\Validator\UnsupportedConstraintException;
use JBen87\ParsleyBundle\Validator\Constraint as ParsleyConstraint;
use JBen87\ParsleyBundle\Validator\Constraints as ParsleyAssert;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class ConstraintFactory
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Constraint $constraint
     *
     * @return ParsleyConstraint
     *
     * @throws UnsupportedConstraintException
     */
    public function create(Constraint $constraint)
    {
        switch (get_class($constraint)) {
            case 'Symfony\Component\Validator\Constraints\Email':
                return $this->createType($constraint, 'email');

            case 'Symfony\Component\Validator\Constraints\Length':
                return $this->createLengthConstraint($constraint);

            case 'Symfony\Component\Validator\Constraints\NotBlank':
                return $this->createRequired($constraint);

            case 'Symfony\Component\Validator\Constraints\Range':
                return $this->createRange($constraint);
        }

        throw new UnsupportedConstraintException($constraint);
    }

    /**
     * @param Constraint $constraint
     *
     * @return ParsleyConstraint
     */
    private function createLengthConstraint(Constraint $constraint)
    {
        if (isset($constraint->min) && isset($constraint->max)) {
            return $this->createLength($constraint);
        }

        if (isset($constraint->min)) {
            return $this->createMinLength($constraint);
        }

        return $this->createMaxLength($constraint);
    }

    /**
     * @param Constraint $constraint
     *
     * @return ParsleyAssert\Length
     */
    private function createLength(Constraint $constraint)
    {
        $options = [
            'min' => $constraint->min,
            'max' => $constraint->max,
        ];

        if ($constraint->min === $constraint->max) {
            $options['message'] = $this->translator->trans($constraint->exactMessage, [], 'validators');
        }

        return new ParsleyAssert\Length($options);
    }

    /**
     * @param Constraint $constraint
     *
     * @return ParsleyAssert\MaxLength
     */
    private function createMaxLength(Constraint $constraint)
    {
        $options = [
            'max' => $constraint->max,
            'message' => $this->translator->trans($constraint->maxMessage, [], 'validators'),
        ];

        return new ParsleyAssert\MaxLength($options);
    }

    /**
     * @param Constraint $constraint
     *
     * @return ParsleyAssert\MinLength
     */
    private function createMinLength(Constraint $constraint)
    {
        $options = [
            'min' => $constraint->min,
            'message' => $this->translator->trans($constraint->minMessage, [], 'validators'),
        ];

        return new ParsleyAssert\MinLength($options);
    }

    /**
     * @param Constraint $constraint
     *
     * @return ParsleyAssert\Range
     */
    private function createRange(Constraint $constraint)
    {
        $options = [];

        if (isset($constraint->min)) {
            $options['min'] = $constraint->min;
            $options['minMessage'] = $this->translator->trans($constraint->minMessage, [], 'validators');
        }

        if (isset($constraint->max)) {
            $options['max'] = $constraint->max;
            $options['maxMessage'] = $this->translator->trans($constraint->maxMessage, [], 'validators');
        }

        return new ParsleyAssert\Range($options);
    }

    /**
     * @param Constraint $constraint
     *
     * @return ParsleyAssert\Required
     */
    private function createRequired(Constraint $constraint)
    {
        $options = ['message' => $this->translator->trans($constraint->message, [], 'validators')];

        return new ParsleyAssert\Required($options);
    }

    /**
     * @param Constraint $constraint
     * @param string $type
     *
     * @return ParsleyAssert\Type
     */
    private function createType(Constraint $constraint, $type)
    {
        $options = [
            'type' => $type,
            'message' => $this->translator->trans($constraint->message, [], 'validators'),
        ];

        return new ParsleyAssert\Type($options);
    }
}