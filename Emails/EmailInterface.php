<?php

namespace Netpeople\JangoMailBundle\Emails;

use Netpeople\JangoMailBundle\Groups\Group;
use Netpeople\JangoMailBundle\Recipients\RecipientInterface;
use Netpeople\JangoMailBundle\Recipients\RecipientsCollection;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of EmailTemplateInterface
 *
 * @author manuel
 */
interface EmailInterface
{

    /**
     * @return string 
     */
    public function getEmailID();

    /**
     * @param string $emailID 
     */
    public function setEmailID($emailID);

    /**
     * @return string
     */
    public function getSubject();

    /**
     * @return string 
     */
    public function getMessage();

    /**
     * @param string $message 
     */
    public function setMessage($message);

    /**
     * @return mixed 
     */
    public function getOptions($name = NULL);

    /**
     *  @return ArrayCollection
     */
    public function getGroups();

    /**
     * @return RecipientsCollection 
     */
    public function getRecipients();
}
