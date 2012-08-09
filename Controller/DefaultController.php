<?php

namespace Netpeople\JangoMailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Netpeople\JangoMailBundle\Form\Type\EmailType;
use Netpeople\JangoMailBundle\Emails\Email;
use Netpeople\JangoMailBundle\Recipients\Recipient;

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

        return array(
            'log' => $log
        );
    }

    /**
     * @Template()
     */
    public function envioAction()
    {
        $email = new Email();

        $email->addRecipient(new Recipient('@gmail.com'));

        $form = $this->createForm(new EmailType(), $email);

        if ($this->getRequest()->getMethod() === 'POST') {
            if ($form->bindRequest($this->getRequest())->isValid()) {
                $email = $form->getData();
                if ($email = $this->get('jango_mail')->send($email)) {
                    $this->get('session')->setFlash('success', "Se envió el Correo Perfectamente (ID: {$email->getEmailID()})");
                } else {
                    $this->get('session')->setFlash('error', $this->get('jango_mail')->getError());
                }
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

}
