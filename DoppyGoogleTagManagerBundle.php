<?php

namespace Doppy\GoogleTagManagerBundle;

use Doppy\GoogleTagManagerBundle\DependencyInjection\CompilerPass\NormalizerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DoppyGoogleTagManagerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new NormalizerCompilerPass());
    }
}
