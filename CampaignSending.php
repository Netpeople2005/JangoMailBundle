<?php

namespace Netpeople\JangoMailBundle;

use Netpeople\JangoMailBundle\Emails\EmailInterface;
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
    /**
     *
     * @var EmailInterface 
     */
    protected $email = NULL;

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

    public function getParametersToSend()
    {
        $config = $this->jangoMail->getConfig();

        return array(
            'Username' => $config['username'],
            'Password' => $config['password'],
            'FromEmail' => $config['fromemail'],
            'FromName' => $config['fromname'],
            'toGroups' => $this->email->getGroup()->getName(),
            'ToGroupFilter' => '', //por ahora nada
            'ToOther' => '', //por ahora nada
            'ToWebDatabase' => '', //por ahora nada
            'Subject' => $this->email->getSubject(),
            'MessagePlain' => $this->email->getMessagePlain(),
            'MessageHTML' => $this->email->getMessageHtml(),
            //'Options' => $this->email->getOptions(),
        );
    }

    public function send()
    {
        if (!($this->getEmail() instanceof EmailInterface)) {
            throw new \Exception('Debe llamar a setEmail() antes de hacer el Envío');
        }
//        if (!($this->getGroup() instanceof Group)) {
//            throw new \Exception('Debe llamar a setGroup() antes de hacer el Envío');
//        }
        try {
            return $this->jangoMail->getJangoInstance()
                            ->SendMassEmail($this->getParametersToSend());
        } catch (SoapFault $e) {
            $this->jangoMail->setError($e->getMessage());
        } catch (\Exception $e) {
            $this->jangoMail->setError($e->getMessage());
        }
        return FALSE;
    }

}