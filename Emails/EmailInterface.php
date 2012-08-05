<?php

namespace Netpeople\JangoMailBundle\Emails;

use Netpeople\JangoMailBundle\Groups\Group;

/**
 * Description of EmailTemplateInterface
 *
 * @author manuel
 */
interface EmailInterface
{

    public function getSubject();

    public function getMessageHtml();

    public function getMessagePlain();

    public function getOptions();

    /**
     * @return Group 
     */
    public function getGroup();
}
