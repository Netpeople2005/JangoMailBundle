<?php

namespace Netpeople\JangoMailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Netpeople\JangoMailBundle\Form\Type\EmailType;
use Netpeople\JangoMailBundle\Emails\Email;
use Netpeople\JangoMailBundle\Recipients\Recipient;
use Symfony\Component\HttpFoundation\Request;

//use Netpeople\JangoMailBundle\Groups\Group;

class DefaultController extends Controller
{

    /**
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Template()
     */
    public function logsAction(Request $request)
    {
        $query = $this->getDoctrine()
            ->getRepository('JangoMailBundle:EmailLogs')
            ->getQueryAll();

        $items = $this->get('knp_paginator')->paginate($query, $request->get('page', 1));

        return array(
            'logs' => $items,
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

        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            $email = $form->getData();
            if ($email = $this->get('jango_mail')->send($email)) {
                $this->get('session')->getFlashBag()
                    ->add('success', "Se envió el Correo Perfectamente (ID: {$email->getEmailID()})");
            } else {
                $this->get('session')->getFlashBag()->add('error', $this->get('jango_mail')->getError());
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    public function reportesAction()
    {

    }

}
