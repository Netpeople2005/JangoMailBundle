JangoMail
====

Bundle que ofrece una API para el envio de correos con jangomail

Instalación
-----------

Usando composer (Previamente instalado) añadir el paquete al composer.json

.. code-block:: json

    {
        "require": {
            "optime/jangomail": "~2.0"
        }
    }

Luego de este ejecutar el comando:

::

    composer update //ó composer install dependiendo de si ya se tiene una instalación previa de paquetes

Registrar el Bundle en el AppKernel

.. code-block:: php

    <?php

    // AppKernel::registerBundles()
    $bundles = array(
        // ...
        new Netpeople\JangoMailBundle\JangoMailBundle(),
        // ...
    );

Agregar la configuración para JANGO en el config.yml

.. code-block:: yml

    #app/config/config.yml 
    jango_mail:
        username: Nombre de usuario de Jango
        password: Contraseña del Usuario en Jango
        fromname: Nombre del Remitente para los Correos
        fromemail: Correo del Remitente
        #enable_log: true ó false # es opcional, por defecto está en false
        #disable_delivery: true ó false #es opcional, por defecto esta en false
        #bcc: #coleccion de correos a los que siempre les llegará el email, es opcional
            # - correo@dominio.com # este seria un ejemplo de correo, por ahora solo trabaja con 1 :-/

Adicional
---------

Ademas de ofrecer un API de comunicación con jango, este bundle ofrece un entorno visual para la gestion y pruebas de la API.

Para lograr esto solo debemos incluir en el archivo routing_dev.yml ( preferiblemente routin_dev para que no esté disponible en produccion ) de la app lo siguiente:

.. code-block:: yml

    #app/config/routing_dev.yml
    _jango_mail:
        resource: "@JangoMailBundle/Resources/config/routing.yml"
        prefix:   /jango

Con la carga de este recurso se tienen displonibles las pruebas y visualizaciones del API.
