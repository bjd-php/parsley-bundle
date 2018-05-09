<?php

namespace JBen87\ParsleyBundle\Constraint\Reader;

class ReaderRegistry
{
    /**
     * @var ReaderInterface[]
     */
    private $readers;

    /**
     * @param ReaderInterface[] $readers
     */
    public function __construct(array $readers)
    {
        usort($readers, function (ReaderInterface $left, ReaderInterface $right) {
            return $left->getPriority() <=> $right->getPriority();
        });

        $this->readers = $readers;
    }

    /**
     * @return ReaderInterface[]
     */
    public function all(): array
    {
        return $this->readers;
    }
}
