<?php

namespace JBen87\ParsleyBundle\Constraint\Reader;

final class ReaderRegistry
{
    /**
     * @var ReaderInterface[]
     */
    private array $readers = [];

    /**
     * @param ReaderInterface[] $readers
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

    public function all(): array
    {
        return $this->readers;
    }
}
