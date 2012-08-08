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
        
        var_dump($this->get('jango_mail'));
        
//        $email->addRecipient(new Recipient('manuel_j555@hotmail.com'))
//                ->setMessage('el mensaje<h3>Ahora con entrega desabilitada :-)</h3>')
//                ->setSubject('el asunto');
//        $email->addGroup(new Group('test'))
//                ->setMessage('Enviando un mensaje a un grupo :-) con correos ocultos')
//                ->setSubject('el asunto del mensaje al grupooooo son las 2:05 pm');
//                
//        var_dump($this->get('jango_mail')->send($email));
//        var_dump($this->get('jango_mail')->getError());
        
        
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
        
        $grupo = new Group('test');
        
        $grupo->addRecipient(new Recipient('programador.manuel@gmail.com'));
        $grupo->addRecipient(new Recipient('apatino@odeveloper.com'));
        $grupo->addRecipient(new Recipient('ohernandez@odeveloper.com'));
        $grupo->addRecipient(new Recipient('jcardozo@odeveloper.com'));
        
        var_dump($adminGrupos->addMembers($grupo));
        var_dump($this->get('jango_mail')->getError());
        
        die;
        return array();
    }

}
