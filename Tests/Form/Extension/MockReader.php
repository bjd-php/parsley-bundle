<?php

namespace JBen87\ParsleyBundle\Tests\Form\Extension;

use JBen87\ParsleyBundle\Constraint\Reader\ReaderInterface;
use Symfony\Component\Form\FormInterface;

final class MockReader implements ReaderInterface
{
    /**
     * @var array
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function read(FormInterface $form): array
    {
        return $this->data;
    }

    public function getPriority(): int
    {
        return 0;
    }
}
