<?php

namespace Netpeople\JangoMailBundle;

use Netpeople\JangoMailBundle\JangoMail;
use Netpeople\JangoMailBundle\Emails\EmailInterface;

/**
 * Description of AbstractSending
 *
 * @author maguirre
 */
abstract class AbstractSending
{

    /**
     *
     * @var JangoMail
     */
    protected $jangoMail;

    /**
     *
     * @var EmailInterface 
     */
    protected $email;

    public function __construct(JangoMail $jangoMail)
    {
        $this->jangoMail = $jangoMail;
    }

    public function setEmail(EmailInterface $email)
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    protected function getOptionsString()
    {
        $opts = array();
        foreach ($this->prepareOptions() as $index => $value) {
            $opts[] = "$index=$value";
        }
        return join(',', $opts);
    }

    protected function prepareOptions()
    {
        $options = ((array) $this->email->getOptions()) + (array) $this
                ->jangoMail->getConfig('options');

        $options = array_intersect_key($options, $this->getValidOptions());

        if (isset($options['BCC']) && is_array($options['BCC']) &&
                is_array($this->jangoMail->getConfig('bcc'))) {
            $options['BCC'] = array_merge($options['BCC'], $this
                    ->jangoMail->getConfig('bcc'));
        }

        foreach ($options as $index => $value) {
            if (null === $value || is_object($value)) {
                unset($options[$index]);
            }
        }

        return $options;
    }

    abstract public function send();

    abstract public function getValidOptions();
}
