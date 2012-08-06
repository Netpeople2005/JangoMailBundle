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
class GroupType extends \Symfony\Component\Form\Extension\Core\Type\ChoiceType
{

    public function buildForm(FormBuilder $form, array $opciones)
    {

        $form->add('name', 'text', array(
            'label' => 'Nombre del Grupo',
        ));
    }

    //put your code here
    public function getName()
    {
        return 'grupo';
    }

    public function getDefaultOptions(array $opciones)
    {
        return array(
            'data_class' => 'Netpeople\JangoMailBundle\Groups\Group'
        );
    }

}
