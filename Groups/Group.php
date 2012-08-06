<?php

namespace Netpeople\JangoMailBundle\Groups;

use Netpeople\JangoMailBundle\Recipients\RecipientInterface;
use Symfony\Component\Validator\Constraints\Type;

/**
 * Description of Group
 *
 * @author manuel
 */
class Group
{

    protected $name;
    protected $groupID;
    protected $memberCount;

    /**
     * @Type(type="Netpeople\JangoMailBundle\Recipients\RecipientInterface")
     * @var array
     */
    protected $recipients = array();

    function __construct($name = NULL)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getGroupID()
    {
        return $this->groupID;
    }

    public function setGroupID($groupID)
    {
        $this->groupID = $groupID;
        return $this;
    }

    public function getMemberCount()
    {
        return $this->memberCount;
    }

    public function setMemberCount($memberCount)
    {
        $this->memberCount = $memberCount;
        return $this;
    }

    public function addRecipient(RecipientInterface $recipient)
    {
        $recipient->setGroup($this);
        $this->recipients[$recipient->getEmail()] = $recipient;
        return $this;
    }

    public function setRecipients($recipients)
    {
        $this->recipients = array();
        foreach ($recipients as $e) {
            $this->addRecipient($e);
        }
        return $this;
    }

    public function getRecipients()
    {
        return $this->recipients;
    }

}