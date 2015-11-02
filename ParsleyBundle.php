<?php

namespace JBen87\ParsleyBundle;

use JBen87\ParsleyBundle\DependencyInjection\Compiler\FormTypeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Benoit Jouhaud <bjouhaud@prestaconcept.net>
 */
class ParsleyBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new FormTypeCompilerPass());
    }
}
