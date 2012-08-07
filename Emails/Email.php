<?php

namespace Netpeople\JangoMailBundle\Emails;

use Netpeople\JangoMailBundle\Emails\EmailInterface;
use Symfony\Component\Validator\Constraints\Type;
use Netpeople\JangoMailBundle\Groups\Group;
use Netpeople\JangoMailBundle\Recipients\RecipientInterface;

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
     * @var type 
     */
    protected $groups;

    /**
     *
     * @var array 
     */
    protected $recipients;

    /**
     *
     * @var array 
     */
    protected $options = array(
        'OpenTrack' => 'True',
        'ClickTrack' => 'True',
    );

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

    public function getMessageHtml()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function getMessagePlain()
    {
        return strip_tags($this->message);
    }

    public function getOptions($name = NULL)
    {
        if ($name) {
            return array_key_exists($name, $this->options) ?
                    $this->options[$name] : NULL;
        }
        return $this->options;
    }

    public function getOptionsString(array $options)
    {
        $options = array();
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
     * @return Group 
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     *
     * @param array $groups
     */
    public function setGroups(array $groups)
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
        $this->groups[$group->getName()] = $group;
        return $this;
    }

    public function getRecipients()
    {
        return $this->recipients;
    }

    public function setRecipients($recipients)
    {
        $this->recipients = $recipients;
        return $this;
    }

    public function addRecipient(RecipientInterface $recipients)
    {
        $this->recipients[$recipients->getEmail()] = $recipients;
        return $this;
    }

}
