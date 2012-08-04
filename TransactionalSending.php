<?php

namespace Netpeople\JangoMailBundle;

use Netpeople\JangoMailBundle\Emails\EmailTemplateInterface;
use Netpeople\JangoMailBundle\JangoMail;
use Netpeople\JangoMailBundle\Groups\Group;
use Netpeople\JangoMailBundle\Recipients\RecipientInterface;

/**
 * Description of CampaignSending
 *
 * @author manuel
 */
class TransactionalSending
{

    protected $jangoMail = NULL;
    
    protected $recipient = NULL;
    
    protected $email = NULL;

    public function __construct(JangoMail $jangoMail)
    {
        $this->jangoMail = $jangoMail;
    }
    
    public function setRecipient(RecipientInterface $recipient){
        $this->recipient = $recipient;
        return $this;
    }
    
    public function getRecipient(){
        return $this->recipient;
    }
    
    public function setEmail(EmailTemplateInterface $email){
        $this->email = $email;
        return $this;
    }
    
    public function getEmail(){
        return $this->email;
    }

    public function getParametersToSend()
    {
        $config = $this->jangoMail->getConfig();

        return array(
            'Username' => $config['username'],
            'Password' => $config['password'],
            'FromEmail' => $config['fromemail'],
            'FromName' => $config['fromname'],
            'toEmailAddress' => $this->getRecipient(),
            'Subject' => $this->email->getSubject(),
            'MessagePlain' => $this->email->getMessagePlain(),
            'MessageHTML' => $this->email->getMessageHtml(),
            'Options' => $this->email->getOptions(),
        );
    }

    public function send()
    {
        try {
            if ( !($this->getEmail() instanceof EmailTemplateInterface) ){
                throw new \Exception('Debe llamar a setEmail() antes de hacer el Envío');
            }
            if ( !($this->getRecipient() instanceof Group) ){
                throw new \Exception('Debe llamar a setRecipient() antes de hacer el Envío');
            }
            return $this->jangoMail->getJangoInstance()
                            ->SendTransactionalEmail($this->getParametersToSend());
        } catch (SoapFault $e) {
            echo $e;
        }
    }

}