<?php

namespace Netpeople\JangoMailBundle\Emails;

use Netpeople\JangoMailBundle\Emails\EmailTemplateInterface;

/**
 * Description of Email
 *
 * @author manuel
 */
class Email implements EmailTemplateInterface
{

    protected $subject;
    protected $message;
    protected $options = array(
        'OpenTrack' => 'True',
        'ClickTrack' => 'True',
    );

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
        foreach($this->options as $index => $value){
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

}
