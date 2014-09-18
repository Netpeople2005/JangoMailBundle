<?php

namespace Netpeople\JangoMailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Netpeople\JangoMailBundle\Groups\Group;
use Netpeople\JangoMailBundle\Form\Type\GroupType;
use Netpeople\JangoMailBundle\Recipients\Recipient;

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
        $grupos = $this->get('jango_mail.group_admin')->getAll();
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
        $newgroup = new Group();
        $newgroup->addRecipient(new Recipient('arp2312@gmail.com'));
        $newgroup->addRecipient(new Recipient());
        $form = $this->createForm(new GroupType(), $newgroup);

        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            $adminGrupos = $this->get('jango_mail.group_admin');
            if ($grupo = $adminGrupos->add($form->getData())) {
                $this->get('session')
                        ->getFlashBag()
                        ->add('success', "Se creó Correctamente el Grupo {$grupo->getGroupID()}");
                        
                return $this->redirect($this->generateUrl('JangoMailBundle_Grupos_index'), 201);
            } else {
                $this->get('session')
                        ->getFlashBag()
                        ->add('error', $this->get('jango_mail')->getError());
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
        $adminGrupos = $this->get('jango_mail.group_admin');

        $grupo = $adminGrupos->getById($grupoID);
        $adminGrupos->getMembers($grupo);

        $form = $this->createForm(new GroupType(), clone $grupo);

//        if ($this->getRequest()->getMethod() === 'POST' &&
//                $form->bindRequest($this->getRequest())->isValid()) {
//            $nuevoGrupo = $form->getData();
//            if ($ID = $adminGrupos->editGroup($grupo, $nuevoGrupo->getName())) {
//                $this->get('session')->getFlashBag()->add('success', "Se Editó Correctamente el Grupo $grupoID");
//                return $this->redirect($this->generateUrl('JangoMailBundle_Grupos_index'), 200);
//            } else {
//                $this->get('session')->getFlashBag()->add('error', $this->get('jango_mail')->getError());
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
        $adminGrupos = $this->get('jango_mail.group_admin');

        $grupo = $adminGrupos->getById($grupoID);

        $miembros = $adminGrupos->getMembers($grupo);

        return array(
            'miembros' => $miembros,
            'grupo' => $grupo,
        );
    }

}