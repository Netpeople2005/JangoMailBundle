<?php

namespace Netpeople\JangoMailBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Netpeople\JangoMailBundle\Form\Type\RecipientType;

/**
 * Description of EmailCampaign
 *
 * @author manuel
 */
class EmailType extends AbstractType
{

    public function buildForm(FormBuilder $form, array $opciones)
    {
        $form->add('subject', 'text', array(
                    'label' => 'Asunto del Correo'
                ))
                ->add('recipients', 'collection', array(
                    'label' => 'Destinatario',
                    'type' => new RecipientType(),
                ))
                ->add('message', 'textarea', array(
                    'label' => 'Cuerpo del Correo'
                ))
        ;
    }

    //put your code here
    public function getName()
    {
        return 'Email2';
    }

    public function getDefaultOptions(array $opciones)
    {
        return array(
            'data_class' => 'Netpeople\JangoMailBundle\Emails\Email'
        );
    }

}
