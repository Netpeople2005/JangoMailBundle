<?php
/**
 * 08/10/2014
 * citrix_mobility
 */

namespace Netpeople\JangoMailBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * @autor Manuel Aguirre <programador.manuel@gmail.com>
 */
class LogsFilterType extends AbstractType
{

    public function getName()
    {
        return 'logs_filter';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('get');

        $builder
            ->add('error', 'text', array(
                'label' => 'Error',
                'required' => false,
            ))
            ->add('result', 'text', array(
                'label' => 'Resultado',
                'required' => false,
            ))
            ->add('subject', 'text', array(
                'label' => 'Asunto',
                'required' => false,
            ))
            ->add('recipient', 'text', array(
                'label' => 'Destinatario',
                'required' => false,
            ))
            ->add('send', 'submit', array(
                'label' => 'Filtrar',
                'attr' => array('class' => 'btn-primary')
            ))
            ->add('clear', 'submit', array(
                'label' => 'Borrar Filtro',
                'attr' => array('class' => 'btn-default')
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }


}