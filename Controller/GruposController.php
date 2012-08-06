<?php

namespace Netpeople\JangoMailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Netpeople\JangoMailBundle\Groups\Group;

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

}