<?php

namespace JBen87\ParsleyBundle\Form\Adapter;

use JBen87\ParsleyBundle\Exception\Validator\ParsleyConstraints\UndefinedParsleyConstraintException;
use JBen87\ParsleyBundle\Validator\ParsleyConstraints;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class ConstraintsAdapter
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Constraint[] $constraints
     *
     * @return array
     */
    public function generateConstraints(array $constraints)
    {
        $parsleyConstraints = [];

        foreach ($constraints as $constraint) {
            $parsleyConstraints[] = $this->generateConstraint($constraint);
        }

        return $parsleyConstraints;
    }

    /**
     * @param Constraint $constraint
     *
     * @return ParsleyConstraints\ParsleyConstraint
     *
     * @throws UndefinedParsleyConstraintException
     */
    protected function generateConstraint(Constraint $constraint)
    {
        switch (get_class($constraint)) {
            case 'Symfony\Component\Validator\Constraints\Email':
                return $this->createType($constraint, 'email');

            case 'Symfony\Component\Validator\Constraints\Length':
                return $this->generateLengthConstraint($constraint);

            case 'Symfony\Component\Validator\Constraints\NotBlank':
                return $this->createRequired($constraint);

            case 'Symfony\Component\Validator\Constraints\Range':
                return $this->createRange($constraint);
        }

        throw new UndefinedParsleyConstraintException($constraint);
    }

    /**
     * @param Constraint $constraint
     *
     * @return ParsleyConstraints\ParsleyConstraint
     */
    protected function generateLengthConstraint(Constraint $constraint)
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
     * @return ParsleyConstraints\Length
     */
    protected function createLength(Constraint $constraint)
    {
        $options = [
            'min'   => $constraint->min,
            'max'   => $constraint->max
        ];

        if ($constraint->min === $constraint->max) {
            $options['message'] = $this->translator->trans($constraint->exactMessage, [], 'validators');
        }

        return new ParsleyConstraints\Length($options);
    }

    /**
     * @param Constraint $constraint
     *
     * @return ParsleyConstraints\MaxLength
     */
    protected function createMaxLength(Constraint $constraint)
    {
        $options = [
            'max'       => $constraint->max,
            'message'   => $this->translator->trans($constraint->maxMessage, [], 'validators')
        ];

        return new ParsleyConstraints\MaxLength($options);
    }

    /**
     * @param Constraint $constraint
     *
     * @return ParsleyConstraints\MinLength
     */
    protected function createMinLength(Constraint $constraint)
    {
        $options = [
            'min'       => $constraint->min,
            'message'   => $this->translator->trans($constraint->minMessage, [], 'validators')
        ];

        return new ParsleyConstraints\MinLength($options);
    }

    /**
     * @param Constraint $constraint
     *
     * @return ParsleyConstraints\Range
     */
    protected function createRange(Constraint $constraint)
    {
        $options = [];

        if (isset($constraint->min)) {
            $options['min']         = $constraint->min;
            $options['minMessage']  = $this->translator->trans($constraint->minMessage, [], 'validators');
        }

        if (isset($constraint->max)) {
            $options['max']         = $constraint->max;
            $options['maxMessage']  = $this->translator->trans($constraint->maxMessage, [], 'validators');
        }

        return new ParsleyConstraints\Range($options);
    }

    /**
     * @param Constraint $constraint
     *
     * @return ParsleyConstraints\Required
     */
    protected function createRequired(Constraint $constraint)
    {
        $options = ['message' => $this->translator->trans($constraint->message, [], 'validators')];

        return new ParsleyConstraints\Required($options);
    }

    /**
     * @param Constraint    $constraint
     * @param string        $type
     *
     * @return ParsleyConstraints\Type
     */
    protected function createType(Constraint $constraint, $type)
    {
        $options = [
            'type'      => $type,
            'message'   => $this->translator->trans($constraint->message, [], 'validators')
        ];

        return new ParsleyConstraints\Type($options);
    }
}
