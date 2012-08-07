<?php

namespace Netpeople\JangoMailBundle\Emails;

use Netpeople\JangoMailBundle\Groups\Group;
use Netpeople\JangoMailBundle\Recipients\RecipientInterface;

/**
 * Description of EmailTemplateInterface
 *
 * @author manuel
 */
interface EmailInterface
{

    public function getEmailID();

    public function setEmailID($emailID);

    public function getSubject();

    public function getMessageHtml();

    public function getMessagePlain();

    public function getOptions($name = NULL);

    public function getOptionsString(array $options);

    public function getGroups();

    public function getRecipients();

    public function setRecipients($recipients);

    public function addRecipient(RecipientInterface $recipients);
}
