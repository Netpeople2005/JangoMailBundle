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
class GroupType extends AbstractType
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

        $form->add('name', 'choice', array(
            'label' => ' ',
            'choice_list' => $this->getGroupAdmin(),
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
