<?php

namespace JBen87\ParsleyBundle;

use JBen87\ParsleyBundle\DependencyInjection\ParsleyExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ParsleyBundle extends Bundle
{
    /**
     * @inheritdoc
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new ParsleyExtension('jben87_parsley');
    }
}
