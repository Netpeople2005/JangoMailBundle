<?php

namespace Netpeople\JangoMailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
//use \Netpeople\JangoMailBundle\Form\Type\EmailCampaignType;
//use Netpeople\JangoMailBundle\Emails\Email;
//use Netpeople\JangoMailBundle\Recipients\Recipient;
//use Netpeople\JangoMailBundle\Groups\Group;

class DefaultController extends Controller
{

    /**
     * @Template()
     */
    public function logsAction()
    {
        $logs = $this->getDoctrine()
                ->getRepository('JangoMailBundle:EmailLogs')
                ->findAll();
     
        return array(
            'logs' => $logs
        );
    }

    /**
     * @Template()
     */
    public function logAction($id)
    {
        $log = $this->getDoctrine()
                ->getRepository('JangoMailBundle:EmailLogs')
                ->findOneById($id);
        
        var_dump($log);die;
     
        return array(
            'logs' => $logs
        );
    }

    /**
     * @Template()
     */
    public function envioAction()
    {
        $email = new \Netpeople\JangoMailBundle\Emails\Email();
        
        $r = new \Netpeople\JangoMailBundle\Recipients\Recipient();
        
        $r->setEmail('programador.manuel@gmail.com');
        
        $email->addRecipient($r)->setSubject('Asunto :-)')
                ->setMessage("Este es el mensaje");
        
        var_dump($this->get('jango_mail')->send($email));
        
        return new \Symfony\Component\HttpFoundation\Response("jejeje");
        
        return array(
            'logs' => $logs
        );
    }

}
