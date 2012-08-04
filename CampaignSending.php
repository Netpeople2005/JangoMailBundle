<?php

namespace Netpeople\JangoMailBundle;

use Netpeople\JangoMailBundle\Emails\EmailTemplateInterface;
use Netpeople\JangoMailBundle\JangoMail;
use Netpeople\JangoMailBundle\Groups\Group;

/**
 * Description of CampaignSending
 *
 * @author manuel
 */
class CampaignSending
{

    protected $jangoMail = NULL;
    
    protected $group = NULL;
    
    protected $email = NULL;

    public function __construct(JangoMail $jangoMail)
    {
        $this->jangoMail = $jangoMail;
    }
    
    public function setGroup(Group $group){
        $this->group = $group;
        return $this;
    }
    
    public function getGroup(){
        return $this->group;
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
            'toGroups' => $this->group->getName(),
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
            if ( !($this->getGroup() instanceof Group) ){
                throw new \Exception('Debe llamar a setGroup() antes de hacer el Envío');
            }
            //var_dump($this->getParametersToSend());die;
            return $this->jangoMail->getJangoInstance()
                            ->SendMassEmail($this->getParametersToSend());
        } catch (SoapFault $e) {
            echo $e . '<br/>';
        }
    }

}