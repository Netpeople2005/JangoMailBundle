<?php

namespace Netpeople\JangoMailBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of GroupType
 *
 * @author manuel
 */
class RecipientType extends AbstractType
{

    public function buildForm(FormBuilderInterface $form, array $opciones)
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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        
        $resolver->setDefaults(array(
            'data_class' => 'Netpeople\\JangoMailBundle\\Recipients\\Recipient'
        ));
    }

}
