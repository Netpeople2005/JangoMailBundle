<?php

namespace Netpeople\JangoMailBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

class FOSJangoPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('fos_user.mailer.default')) {
            return;
        }
        $container->setParameter('jango_mail_fos.config', array(
            'template' => array(
                'confirmation' => '%fos_user.registration.confirmation.template%',
                'resetting' => '%fos_user.resetting.email.template%',
            ),
            'from_email' => array(
                'confirmation' => '%fos_user.registration.confirmation.from_email%',
                'resetting' => '%fos_user.resetting.email.from_email%',
            ),
        ));
        $definition = $container->getDefinition('fos_user.mailer.default');
        $definition->setClass('%jango_mail_fos.class%');
        $definition->setArguments(array(
            new Reference('jango_mail'),
            new Reference("router"),
            new Reference("twig"),
            "%jango_mail_fos.config%",
            new Reference("logger", ContainerInterface::IGNORE_ON_INVALID_REFERENCE),
        ));
    }

}
