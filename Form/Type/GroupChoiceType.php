<?php

namespace Netpeople\JangoMailBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of GroupType
 *
 * @author manuel
 */
class GroupType extends ChoiceType
{

    public function buildForm(FormBuilderInterface $form, array $opciones)
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
