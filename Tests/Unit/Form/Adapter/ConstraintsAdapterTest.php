<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Form\Adapter;

use JBen87\ParsleyBundle\Form\Adapter\ConstraintsAdapter;
use JBen87\ParsleyBundle\Validator\ParsleyConstraints\ParsleyConstraintInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 *
 * bin/phpunit -c app/ src/ParsleyBundle/Tests/Unit/Form/Adapter/ConstraintsAdapterTest.php
 */
class ConstraintsAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Constraint[]
     */
    protected $constraints;

    /**
     * @var ParsleyConstraintInterface[]
     */
    protected $parsleyConstraints;

    /**
     * @var array
     */
    protected $translations;

    public function __construct()
    {
        $this->constraints = [
            new Constraints\NotBlank(),
            new Constraints\Email(['message' => 'Invalid email address.']),
            new Constraints\Length(['min' => 1, 'max' => 1]),
            new Constraints\Length(['min' => 3]),
            new Constraints\Length(['max' => 4]),
            new Constraints\Range(['min' => 2, 'max' => 5])
        ];

        $this->translations = [
            'This value should not be blank.'   => 'Cette valeur ne doit pas être vide.',
            'Invalid email address.'            => 'Adresse email invalide.',
            sprintf(
                '%s%s',
                'This value should have exactly {{ limit }} character.|',
                'This value should have exactly {{ limit }} characters.'
            )                                   => sprintf(
                '%s%s',
                'Cette chaine doit avoir exactement {{ limit }} caractère.|',
                'Cette chaine doit avoir exactement {{ limit }} caractères.'
            ),
            sprintf(
                '%s%s',
                'This value is too short. It should have {{ limit }} character or more.|',
                'This value is too short. It should have {{ limit }} characters or more.'
            )                                   => sprintf(
                '%s%s',
                'Cette chaine est trop courte. Elle doit avoir au minimum {{ limit }} caractère.|',
                'Cette chaine est trop courte. Elle doit avoir au minimum {{ limit }} caractères.'
            ),
            sprintf(
                '%s%s',
                'This value is too long. It should have {{ limit }} character or less.|',
                'This value is too long. It should have {{ limit }} characters or less.'
            )                                   => sprintf(
                '%s%s',
                'Cette chaine est trop longue. Elle doit avoir au maximum {{ limit }} caractère.|',
                'Cette chaine est trop longue. Elle doit avoir au maximum {{ limit }} caractères.'
            ),
            sprintf(
                '%s',
                'This value should be {{ limit }} or more.'
            )                                   => sprintf(
                '%s',
                'Cette valeur doit être supérieure ou égale à {{ limit }}.'
            ),
            sprintf(
                '%s',
                'This value should be {{ limit }} or less.'
            )                                   => sprintf(
                '%s',
                'Cette valeur doit être inférieure ou égale à {{ limit }}.'
            )
        ];

        $this->parsleyConstraints = [];
    }

    public function testGenerateConstraints()
    {
        $constraintsAdapter         = $this->createConstraintsAdapter();
        $this->parsleyConstraints   = $constraintsAdapter->generateConstraints($this->constraints);

        $this->assertCount(count($this->constraints), $this->parsleyConstraints);

        $this->assertRequired($this->parsleyConstraints[0]);
        $this->assertTypeEmail($this->parsleyConstraints[1]);
        $this->assertLength($this->parsleyConstraints[2]);
        $this->assertMinLength($this->parsleyConstraints[3]);
        $this->assertMaxLength($this->parsleyConstraints[4]);
        $this->assertRange($this->parsleyConstraints[5]);
    }

    /**
     * @return ConstraintsAdapter
     */
    protected function createConstraintsAdapter()
    {
        $translator = $this->createTranslator();

        return new ConstraintsAdapter($translator->reveal());
    }

    /**
     * @return TranslatorInterface
     */
    protected function createTranslator()
    {
        $translator = $this->prophesize('Symfony\Component\Translation\Translator');

        foreach ($this->translations as $key => $translation) {
            $translator->trans($key, [], 'validators')->willReturn($translation);
        }

        return $translator;
    }

    /**
     * @param ParsleyConstraintInterface $constraint
     */
    protected function assertRequired(ParsleyConstraintInterface $constraint)
    {
        $this->assertInstanceOf('JBen87\ParsleyBundle\Validator\ParsleyConstraints\Required', $constraint);
        $this->assertEquals([
            'data-parsley-required'         => 'true',
            'data-parsley-required-message' => 'Cette valeur ne doit pas être vide.'
        ], $constraint->toArray());
        $this->assertEquals(
            'data-parsley-required="true" data-parsley-required-message="Cette valeur ne doit pas être vide."',
            $constraint->render()
        );
    }

    /**
     * @param ParsleyConstraintInterface $constraint
     */
    protected function assertTypeEmail(ParsleyConstraintInterface $constraint)
    {
        $this->assertInstanceOf('JBen87\ParsleyBundle\Validator\ParsleyConstraints\Type', $constraint);
        $this->assertEquals([
            'data-parsley-type'         => 'email',
            'data-parsley-type-message' => 'Adresse email invalide.'
        ], $constraint->toArray());
        $this->assertEquals(
            'data-parsley-type="email" data-parsley-type-message="Adresse email invalide."',
            $constraint->render()
        );
    }

    /**
     * @param ParsleyConstraintInterface $constraint
     */
    protected function assertLength(ParsleyConstraintInterface $constraint)
    {
        $this->assertInstanceOf('JBen87\ParsleyBundle\Validator\ParsleyConstraints\Length', $constraint);
        $this->assertEquals([
            'data-parsley-length'           => '[1, 1]',
            'data-parsley-length-message'   => sprintf(
                '%s%s',
                'Cette chaine doit avoir exactement {{ limit }} caractère.|',
                'Cette chaine doit avoir exactement {{ limit }} caractères.'
            )
        ], $constraint->toArray());
        $this->assertEquals(
            sprintf(
                '%s %s%s%s',
                'data-parsley-length="[1, 1]"',
                'data-parsley-length-message="',
                'Cette chaine doit avoir exactement {{ limit }} caractère.|',
                'Cette chaine doit avoir exactement {{ limit }} caractères."'
            ),
            $constraint->render()
        );
    }

    /**
     * @param ParsleyConstraintInterface $constraint
     */
    protected function assertMinLength(ParsleyConstraintInterface $constraint)
    {
        $this->assertInstanceOf('JBen87\ParsleyBundle\Validator\ParsleyConstraints\MinLength', $constraint);
        $this->assertEquals([
            'data-parsley-minlength'            => '3',
            'data-parsley-minlength-message'    => sprintf(
                '%s%s',
                'Cette chaine est trop courte. Elle doit avoir au minimum {{ limit }} caractère.|',
                'Cette chaine est trop courte. Elle doit avoir au minimum {{ limit }} caractères.'
            )
        ], $constraint->toArray());
        $this->assertEquals(
            sprintf(
                '%s %s%s%s',
                'data-parsley-minlength="3"',
                'data-parsley-minlength-message="',
                'Cette chaine est trop courte. Elle doit avoir au minimum {{ limit }} caractère.|',
                'Cette chaine est trop courte. Elle doit avoir au minimum {{ limit }} caractères."'
            ),
            $constraint->render()
        );
    }

    /**
     * @param ParsleyConstraintInterface $constraint
     */
    protected function assertMaxLength(ParsleyConstraintInterface $constraint)
    {
        $this->assertInstanceOf('JBen87\ParsleyBundle\Validator\ParsleyConstraints\MaxLength', $constraint);
        $this->assertEquals([
            'data-parsley-maxlength'            => '4',
            'data-parsley-maxlength-message'    => sprintf(
                '%s%s',
                'Cette chaine est trop longue. Elle doit avoir au maximum {{ limit }} caractère.|',
                'Cette chaine est trop longue. Elle doit avoir au maximum {{ limit }} caractères.'
            )
        ], $constraint->toArray());
        $this->assertEquals(
            sprintf(
                '%s %s%s%s',
                'data-parsley-maxlength="4"',
                'data-parsley-maxlength-message="',
                'Cette chaine est trop longue. Elle doit avoir au maximum {{ limit }} caractère.|',
                'Cette chaine est trop longue. Elle doit avoir au maximum {{ limit }} caractères."'
            ),
            $constraint->render()
        );
    }

    /**
     * @param ParsleyConstraintInterface $constraint
     */
    protected function assertRange(ParsleyConstraintInterface $constraint)
    {
        $this->assertInstanceOf('JBen87\ParsleyBundle\Validator\ParsleyConstraints\Range', $constraint);
        $this->assertEquals([
            'data-parsley-min'          => '2',
            'data-parsley-min-message'  => 'Cette valeur doit être supérieure ou égale à {{ limit }}.',
            'data-parsley-max'          => '5',
            'data-parsley-max-message'  => 'Cette valeur doit être inférieure ou égale à {{ limit }}.'
        ], $constraint->toArray());
        $this->assertEquals(
            sprintf(
                '%s %s %s %s',
                'data-parsley-min="2"',
                'data-parsley-min-message="Cette valeur doit être supérieure ou égale à {{ limit }}."',
                'data-parsley-max="5"',
                'data-parsley-max-message="Cette valeur doit être inférieure ou égale à {{ limit }}."'
            ),
            $constraint->render()
        );
    }
}
