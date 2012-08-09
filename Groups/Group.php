<?php

namespace Netpeople\JangoMailBundle\Groups;

use Netpeople\JangoMailBundle\Recipients\RecipientInterface;
use \Doctrine\Common\Collections\ArrayCollection;

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
     * @var ArrayCollection
     */
    protected $recipients = array();

    function __construct($name = NULL)
    {
        $this->name = $name;
        $this->recipients = new ArrayCollection();
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

    /**
     *
     * @param RecipientInterface $recipient
     * @return \Netpeople\JangoMailBundle\Groups\Group 
     */
    public function addRecipient(RecipientInterface $recipient)
    {
        if (!$this->recipients->contains($recipient)) {
            $recipient->setGroup($this);
            $this->recipients->add($recipient);
        }
        return $this;
    }

    /**
     *
     * @param ArrayCollection $recipients
     * @return \Netpeople\JangoMailBundle\Groups\Group 
     */
    public function setRecipients(ArrayCollection $recipients)
    {
        $this->recipients = $recipients;
        return $this;
    }

    /**
     *
     * @return ArrayCollection 
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

}