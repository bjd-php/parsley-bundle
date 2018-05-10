<?php

namespace JBen87\ParsleyBundle\Constraint\Reader;

class ReaderRegistry
{
    /**
     * @var ReaderInterface[]
     */
    private $readers;

    /**
     * @param ReaderInterface[]|iterable $readers
     */
    public function __construct(iterable $readers)
    {
        foreach ($readers as $reader) {
            $this->readers[] = $reader;
        }

        usort($this->readers, function (ReaderInterface $left, ReaderInterface $right) {
            return $left->getPriority() <=> $right->getPriority();
        });
    }

    /**
     * @return ReaderInterface[]
     */
    public function all(): array
    {
        return $this->readers;
    }
}
