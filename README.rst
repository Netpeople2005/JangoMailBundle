JangoMail
====

Bundle que ofrece una API para el envio de correos con jangomail

Instalación
-----------

Descargar el repositorio y colocarlo en:

::

    ProyectoSymfony/vendors/bundles/Netpeople/JangoMailBundle

Agregar el Espacio de Nombres al autoloades

::

    <?php

    // app/autoload.php
    $loader->registerNamespaces(array(
        // ...
        'Netpeople'         => __DIR__.'/../vendor/bundles',
        // ...
    ));

Registrar el Bundle en el AppKernel

::

    <?php

    // AppKernel::registerBundles()
    $bundles = array(
        // ...
        new Netpeople\JangoMailBundle\JangoMailBundle(),
        // ...
    );

Agregar la configuración para JANGO en el config.yml

::

    jango_mail:
        userame: Nombre de usuario de Jango
        password: Contraseña del Usuario en Jango
        fromname: Nombre del Remitente para los Correos
        fromemail: Correo del Remitente

Adicional
---------

Ademas de ofrecer un API de comunicacion con jango, este bundle ofrece un entorno visual para la gestion de 