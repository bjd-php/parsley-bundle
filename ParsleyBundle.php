<?php

namespace JBen87\ParsleyBundle;

use JBen87\ParsleyBundle\DependencyInjection\ParsleyExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class ParsleyBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new ParsleyExtension('jben87_parsley');
    }
}
