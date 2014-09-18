<?php

namespace Netpeople\JangoMailBundle\Form\Type;

use Netpeople\JangoMailBundle\Form\Type\RecipientType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of EmailCampaign
 *
 * @author manuel
 */
class EmailType extends AbstractType
{

    public function buildForm(FormBuilderInterface $form, array $opciones)
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
            ->add('Enviar', 'submit', array(
                'attr' => array('class' => 'btn-primary')
            ));
    }

    //put your code here
    public function getName()
    {
        return 'Email2';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'data_class' => 'Netpeople\\JangoMailBundle\\Emails\\Email'
        ));
    }

}
