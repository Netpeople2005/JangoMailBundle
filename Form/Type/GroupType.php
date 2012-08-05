<?php

namespace Netpeople\JangoMailBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * Description of GroupType
 *
 * @author manuel
 */
class GroupType extends AbstractType
{

    public function buildForm(FormBuilder $form, array $opciones)
    {
        $form->add('name', 'text', array(
            'label' => ' '
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
