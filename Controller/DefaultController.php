<?php

namespace Netpeople\JangoMailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Netpeople\JangoMailBundle\Form\Type\EmailCampaignType;
use Netpeople\JangoMailBundle\Emails\Email;
use Netpeople\JangoMailBundle\Recipients\Recipient;
use Netpeople\JangoMailBundle\Groups\Group;

class DefaultController extends Controller
{

    public function indexAction($name)
    {
        $email = new Email();
        
        $email->addRecipient(new Recipient('manuel_j555@hotmail.com'))
                ->setMessage('el mensaje')->setSubject('el asunto');
                
        var_dump($this->get('jango_mail')->send($email));
        
        
        return $this->render('JangoMailBundle:Default:index.html.twig', array('name' => $name));
    }

    /**
     * @Template() 
     */
    public function sendAction()
    {
        $form = $this->createForm(new EmailCampaignType($this->get('jango_mail')->getGroupAdmin()), new Email());

        if ($this->getRequest()->getMethod() == 'POST') {
            if ($form->bindRequest($this->getRequest())->isValid()) {
                var_dump($form->getData());
                //var_dump($this->get('jango_mail')->send($form->getData()));
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * @Template() 
     */
    public function crearGrupoAction()
    {
        
        
        return array();
    }

    /**
     * @Template() 
     */
    public function agregarMiembrosAction()
    {
        /* @var $adminGrupos \Netpeople\JangoMailBundle\Groups\GroupAdmin  */
        $adminGrupos = $this->get('jango_mail')->getGroupAdmin();
        
        $grupo = new Group('pruebas');
        
        $grupo->addRecipient(new Recipient('correo1@hola.com','Manuel Trabajo'));
        $grupo->addRecipient(new Recipient('correo2@hola.com','Manuel Trabajo'));
        $grupo->addRecipient(new Recipient('correo3@hola.com','Manuel Trabajo'));
        $grupo->addRecipient(new Recipient('correo4@hola.com','Manuel Trabajo'));
        
        var_dump($adminGrupos->addMembers($grupo));
        
        die;
        return array();
    }

}
