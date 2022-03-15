<?php

namespace JBen87\ParsleyBundle;

use JBen87\ParsleyBundle\DependencyInjection\JBen87ParsleyExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class JBen87ParsleyBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new JBen87ParsleyExtension('jben87_parsley');
    }
}
