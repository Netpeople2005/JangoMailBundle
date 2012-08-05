<?php

namespace Netpeople\JangoMailBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Netpeople\JangoMailBundle\Form\Type\GroupType;
use Netpeople\JangoMailBundle\Groups\GroupAdmin;

/**
 * Description of EmailCampaign
 *
 * @author manuel
 */
class EmailCampaignType extends AbstractType
{

    protected $groupAdmin = NULL;

    public function __construct(GroupAdmin $groupAdmin)
    {
        $this->setGroupAdmin($groupAdmin);
    }

    public function getGroupAdmin()
    {
        return $this->groupAdmin;
    }

    public function setGroupAdmin($groupAdmin)
    {
        $this->groupAdmin = $groupAdmin;
    }

    public function buildForm(FormBuilder $form, array $opciones)
    {
        $form->add('group', new GroupType($this->getGroupAdmin()), array(
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
