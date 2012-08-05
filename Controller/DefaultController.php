<?php

namespace Netpeople\JangoMailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Netpeople\JangoMailBundle\Form\Type\EmailCampaignType;
use Netpeople\JangoMailBundle\Emails\Email;

class DefaultController extends Controller
{

    public function indexAction($name)
    {
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
                var_dump($this->get('jango_mail')->send($form->getData()));
            }
        }

        return array('form' => $form->createView());
    }

}
