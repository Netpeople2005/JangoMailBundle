<?php

namespace Netpeople\JangoMailBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Netpeople\JangoMailBundle\Form\Type\GroupType;

/**
 * Description of EmailCampaign
 *
 * @author manuel
 */
class EmailCampaignType extends AbstractType
{

    public function buildForm(FormBuilder $form, array $opciones)
    {
        $form->add('group', new GroupType(), array(
                    'label' => 'Enviar al Grupo'
                ))
                ->add('subject', 'text', array(
                    'label' => 'Asunto del Correo'
                ))
                ->add('message', 'textarea', array(
                    'label' => 'Cuerpo del Correo'
                ))
        ;
    }

    //put your code here
    public function getName()
    {
        return 'EmailCampaign';
    }
    
    public function getDefaultOptions(array $opciones)
    {
        return array(
            'data_class' => 'Netpeople\JangoMailBundle\Emails\Email'
        );
    }

}
