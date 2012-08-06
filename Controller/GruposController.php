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