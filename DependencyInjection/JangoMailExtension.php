<?php

namespace Netpeople\JangoMailBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class JangoMailExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        /*
         *  Verificamos que los parametros necesarios para configurar 
         *  jango esten especificados. 
         */
        if (!isset($config['username']) || empty($config['username'])){
            throw new \InvalidArgumentException('El parametro username es necesario para trabajar con JangoMail');
        }
        if (!isset($config['password']) || empty($config['password'])){
            throw new \InvalidArgumentException('El parametro <b>password</b> es necesario para trabajar con JangoMail');
        }
        if (!isset($config['fromemail']) || empty($config['fromemail'])){
            throw new \InvalidArgumentException('El parametro <b>fromemail</b> es necesario para trabajar con JangoMail');
        }
        if (!isset($config['fromname']) || empty($config['fromname'])){
            throw new \InvalidArgumentException('El parametro <b>fromname</b> es necesario para trabajar con JangoMail');
        }
        

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        
        //si estan especificados los parametros procedemos a pasarlos a los
        //parametros del servicio jango_mail        
        $config += $container->getParameter('jango_mail.config');
        $container->setParameter('jango_mail.config', $config);
    }
}
