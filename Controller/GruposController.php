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
            if ($grupo = $adminGrupos->addGroup($form->getData())) {
                $this->get('session')
                        ->setFlash('success', "Se creó Correctamente el Grupo {$grupo->getGroupID()}");
                return $this->redirect($this->generateUrl('JangoMailBundle_Grupos_index'), 201);
            } else {
                $this->get('session')->setFlash('error', $this->get('jango_mail')->getError());
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
        /* @var $adminGrupos \Netpeople\JangoMailBundle\Groups\GroupAdmin */
        $adminGrupos = $this->get('jango_mail')->getGroupAdmin();

        $grupo = $adminGrupos->getGroupByGroupID($grupoID);

        $form = $this->createForm(new GroupType(), clone $grupo);

//        if ($this->getRequest()->getMethod() === 'POST' &&
//                $form->bindRequest($this->getRequest())->isValid()) {
//            $nuevoGrupo = $form->getData();
//            if ($ID = $adminGrupos->editGroup($grupo, $nuevoGrupo->getName())) {
//                $this->get('session')->setFlash('success', "Se Editó Correctamente el Grupo $grupoID");
//                return $this->redirect($this->generateUrl('JangoMailBundle_Grupos_index'), 200);
//            } else {
//                $this->get('session')->setFlash('error', $this->get('jango_mail')->getError());
//            }
//        }

        return array(
            'form' => $form->createView(),
            'grupoID' => $grupoID,
        );
    }

    /**
     * @Template()
     * @return type 
     */
    public function miembrosAction($grupoID)
    {
        /* @var $adminGrupos \Netpeople\JangoMailBundle\Groups\GroupAdmin */
        $adminGrupos = $this->get('jango_mail')->getGroupAdmin();
        
        $grupo = $adminGrupos->getGroupByGroupID($grupoID);
        
        $miembros = $adminGrupos->getMembers($grupo);

        return array(
            'miembros' => $miembros,
            'grupo' => $grupo,
        );
    }

}