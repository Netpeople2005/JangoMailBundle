<?php

namespace Netpeople\JangoMailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Netpeople\JangoMailBundle\Groups\Group;
use Netpeople\JangoMailBundle\Form\Type\GroupType;

/**
 * Description of GruposController
 *
 * @author manuel
 */
class GruposController extends Controller
{

    /**
     * @Template()
     * @return type 
     */
    public function indexAction()
    {
        $grupos = $this->get('jango_mail')->getGroupAdmin()->getGroups();
        return array(
            'grupos' => $grupos
        );
    }

    /**
     * @Template()
     * @return type 
     */
    public function crearAction()
    {
        $form = $this->createForm(new GroupType(), new Group());

        if ($this->getRequest()->getMethod() === 'POST' &&
                $form->bindRequest($this->getRequest())->isValid()) {
            /* @var $adminGrupos \Netpeople\JangoMailBundle\Groups\GroupAdmin */
            $adminGrupos = $this->get('jango_mail')->getGroupAdmin();
            if ($adminGrupos->addGroup($form->getData())) {
                
            }else{
                $error = new \Symfony\Component\Form\FormError($this->get('jango_mail')->getError());
                $form->addError($error);
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Template()
     * @return type 
     */
    public function editarAction($grupoID)
    {
        $form = $this->createForm(new GroupType(), new Group());

        return array(
            'form' => $form->createView()
        );
    }

}