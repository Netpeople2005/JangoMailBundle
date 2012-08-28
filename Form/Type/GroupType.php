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
class GroupType extends AbstractType {

    public function buildForm(FormBuilder $form, array $opciones) {

        $form->add('name', 'text', array(
                    'label' => 'Nombre del Grupo',
                ))
                ->add('recipients', 'collection', array(
                    'label' => 'Destinatario',
                    'type' => new RecipientType(),
                ));
    }

    //put your code here
    public function getName() {
        return 'grupo';
    }

    public function getDefaultOptions(array $opciones) {
        return array(
            'data_class' => 'Netpeople\JangoMailBundle\Groups\Group'
        );
    }

}
