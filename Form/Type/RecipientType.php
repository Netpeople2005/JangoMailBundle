<?php

namespace Netpeople\JangoMailBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Netpeople\JangoMailBundle\Groups\GroupAdmin;

/**
 * Description of GroupType
 *
 * @author manuel
 */
class RecipientType extends AbstractType
{

    public function buildForm(FormBuilder $form, array $opciones)
    {

        $form->add('email', 'email', array(
                    'label' => 'Correo',
                ))
                ->add('name', 'text', array(
                    'label' => 'Nombre',
                ))
        ;
    }

    //put your code here
    public function getName()
    {
        return 'recipient';
    }

    public function getDefaultOptions(array $opciones)
    {
        return array(
            'data_class' => 'Netpeople\JangoMailBundle\Recipients\Recipient'
        );
    }

}
