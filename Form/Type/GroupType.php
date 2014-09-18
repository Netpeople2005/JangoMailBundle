<?php

namespace Netpeople\JangoMailBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of GroupType
 *
 * @author manuel
 */
class GroupType extends AbstractType
{

    public function buildForm(FormBuilderInterface $form, array $opciones)
    {

        $form->add('name', 'text', array(
            'label' => 'Nombre del Grupo',
        ))
            ->add('recipients', 'collection', array(
                'label' => 'Destinatario',
                'type' => new RecipientType(),
            ))
            ->add('Enviar', 'submit', array(
                'attr' => array('class' => 'btn-primary')
            ));;
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
