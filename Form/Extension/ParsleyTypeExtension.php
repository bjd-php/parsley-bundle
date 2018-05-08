<?php

namespace JBen87\ParsleyBundle\Form\Extension;

use JBen87\ParsleyBundle\Exception\Validator\ConstraintException;
use JBen87\ParsleyBundle\Factory\ChainFactory;
use JBen87\ParsleyBundle\Validator\ConstraintsReader\ConstraintsReaderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ParsleyTypeExtension extends AbstractTypeExtension
{
    /**
     * @var ChainFactory
     */
    private $factory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * @var ConstraintsReaderInterface[]
     */
    private $readers;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var string
     */
    private $triggerEvent;

    /**
     * @param ChainFactory $factory
     * @param LoggerInterface $logger
     * @param NormalizerInterface $normalizer
     * @param ConstraintsReaderInterface[] $readers
     * @param bool $enabled
     * @param string $triggerEvent
     */
    public function __construct(
        ChainFactory $factory,
        LoggerInterface $logger,
        NormalizerInterface $normalizer,
        array $readers,
        bool $enabled,
        string $triggerEvent
    ) {
        usort($readers, function (ConstraintsReaderInterface $left, ConstraintsReaderInterface $right) {
            return $left->getPriority() <=> $right->getPriority();
        });

        $this->factory = $factory;
        $this->logger = $logger;
        $this->normalizer = $normalizer;
        $this->readers = $readers;
        $this->enabled = $enabled;
        $this->triggerEvent = $triggerEvent;
    }

    /**
     * @inheritdoc
     */
    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        if (false === $options['parsley_enabled']) {
            return;
        }

        // enable parsley validation on root form
        if (true === $form->isRoot()) {
            $view->vars['attr'] += [
                'novalidate' => true,
                'data-parsley-validate' => true,
            ];

            return;
        }

        // set trigger event attribute on form children
        $view->vars['attr']['data-parsley-trigger'] = $options['parsley_trigger_event'];

        // build constraints and map them as data attributes
        foreach ($this->getConstraints($form) as $symfonyConstraint) {
            try {
                $parsleyConstraint = $this->factory->create($symfonyConstraint);

                $view->vars['attr'] = array_merge($view->vars['attr'], $parsleyConstraint->normalize($this->normalizer));
            } catch (ConstraintException $exception) {
                $this->logger->warning($exception->getMessage(), ['constraint' => $symfonyConstraint]);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'parsley_enabled' => $this->enabled,
            'parsley_trigger_event' => $this->triggerEvent,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getExtendedType(): string
    {
        return FormType::class;
    }

    /**
     * Form constraints should override entity constraints.
     *
     * @param FormInterface $form
     *
     * @return SymfonyConstraint[]
     */
    private function getConstraints(FormInterface $form): array
    {
        $constraints = [];
        foreach ($this->readers as $reader) {
            $constraints = array_merge($constraints, $reader->read($form));
        }

        return $constraints;
    }
}
