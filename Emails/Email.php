<?php

namespace Netpeople\JangoMailBundle\Emails;

use Netpeople\JangoMailBundle\Emails\EmailInterface;
use Symfony\Component\Validator\Constraints\Type;
use Netpeople\JangoMailBundle\Groups\Group;
use Netpeople\JangoMailBundle\Recipients\RecipientInterface;
use \Doctrine\Common\Collections\ArrayCollection;
use \Netpeople\JangoMailBundle\Recipients\RecipientsCollection;

/**
 * Description of Email
 *
 * @author manuel
 */
class Email implements EmailInterface
{

    /**
     *
     * @var string 
     */
    protected $emailID;

    /**
     *
     * @var string
     */
    protected $subject;

    /**
     *
     * @var sring
     */
    protected $message;

    /**
     * 
     * @var ArrayCollection 
     */
    protected $groups;

    /**
     *
     * @var ArrayCollection  
     */
    protected $recipients;

    /**
     *
     * @var array 
     */
    protected $options = array();

    function __construct()
    {
        $this->recipients = new RecipientsCollection();
        $this->groups = new ArrayCollection();
    }

    public function getEmailID()
    {
        return $this->emailID;
    }

    public function setEmailID($emailID)
    {
        $this->emailID = $emailID;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function getOptions($name = NULL)
    {
        if ($name) {
            return array_key_exists($name, $this->options) ?
                    $this->options[$name] : NULL;
        }
        return $this->options;
    }

    public function getOptionsString(array $options = array())
    {
        $this->options += $options;
        foreach ($this->options as $index => $value) {
            $options[] = "$index=$value";
        }
        return join(',', $options);
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     *
     * @return ArrayCollection 
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     *
     * @param ArrayCollection $groups
     */
    public function setGroups(ArrayCollection $groups)
    {
        $this->groups = $groups;
        return $this;
    }

    /**
     *
     * @param Group $group 
     */
    public function addGroup(Group $group)
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
        }
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

    public function setRecipients($recipients)
    {
        $this->recipients = $recipients;
        return $this;
    }

    public function addRecipient(RecipientInterface $recipient)
    {
        if (!$this->recipients->contains($recipient)) {
            $this->recipients->add($recipient);
        }
        return $this;
    }

}
