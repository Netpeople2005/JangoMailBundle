<?php

namespace Netpeople\JangoMailBundle\Emails;

use Netpeople\JangoMailBundle\Emails\EmailInterface;
use Symfony\Component\Validator\Constraints\Type;
use Netpeople\JangoMailBundle\Groups\Group;

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
    protected $subject;

    /**
     *
     * @var sring
     */
    protected $message;

    /**
     * @Type(type="Netpeople\JangoMailBundle\Groups\Group")
     * @var type 
     */
    protected $group;

    /**
     *
     * @var type 
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

    public function getOptions()
    {
        $options = array();
        foreach ($this->options as $index => $value) {
            $options[] = "$index=$value";
        }
        return join(',', $options);
    }

    public function setOptions($options)
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
    public function getGroup()
    {
        return $this->group;
    }
    
    /**
     *
     * @param Group $group 
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;
        return $this;
    }

}
