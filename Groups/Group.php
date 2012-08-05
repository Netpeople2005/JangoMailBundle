<?php

namespace Netpeople\JangoMailBundle\Groups;

use Netpeople\JangoMailBundle\Recipients\RecipientInterface;

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

    function __construct($name = NULL)
    {
        $this->name = $name;
    }

    /**
     * @var array
     */
    protected $recipients;

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

    public function getRecipients()
    {
        return $this->recipients;
    }

}