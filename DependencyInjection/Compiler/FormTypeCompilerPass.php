<?php

namespace JBen87\ParsleyBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
class FormTypeCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $constraintBuilder = $container->findDefinition('jben87_parsley.builder.constraint');
        $normalizer = $container->findDefinition('serializer.normalizer.object');

        $taggedServices = $container->findTaggedServiceIds('form.type');

        foreach ($taggedServices as $id => $tags) {
            $form = $container->findDefinition($id);

            if ($form->hasMethodCall('setConstraintBuilder')) {
                $form->addMethodCall('setConstraintBuilder', $constraintBuilder);
            }

            if ($form->hasMethodCall('setNormalizer')) {
                $form->addMethodCall('setNormalizer', $normalizer);
            }
        }
    }
}
