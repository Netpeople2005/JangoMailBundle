<?php

namespace Netpeople\JangoMailBundle;

use Netpeople\JangoMailBundle\DependencyInjection\Compiler\FOSJangoPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class JangoMailBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FOSJangoPass());
    }

}
