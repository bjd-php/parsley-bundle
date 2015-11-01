<?php

namespace JBen87\ParsleyBundle\Builder;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
interface BuilderInterface
{
    /**
     * @return mixed
     */
    public function build();

    /**
     * @param array $options
     */
    public function configure(array $options);
}
