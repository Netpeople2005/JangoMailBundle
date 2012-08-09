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

        $toGroups = array();
        foreach ($this->email->getGroups() as $group) {
            $toGroups[] = $group->getName();
        }

        return array(
            'Username' => $config['username'],
            'Password' => $config['password'],
            'FromEmail' => $config['fromemail'],
            'FromName' => $config['fromname'],
            'ToGroups' => join(',', $toGroups),
            'ToGroupFilter' => '', //por ahora nada
            'ToOther' => '', //por ahora nada
            'ToWebDatabase' => '', //por ahora nada
            'Subject' => $this->email->getSubject(),
            'MessagePlain' => $this->email->getMessagePlain(),
            'MessageHTML' => $this->email->getMessageHtml(),
            'Options' => $this->email->getOptionsString(array(
                'BCC' => join(';', $config['bcc'])
            )),
        );
    }

    public function send()
    {
        $result = FALSE;
        if (!($this->getEmail() instanceof EmailInterface)) {
            throw new \Exception('Debe llamar a setEmail() antes de hacer el Envío');
        }
        if (!count($this->getEmail()->getGroups())) {
            throw new \Exception('Debe agregar al menos un grupo antes de hacer el Envío');
        }
        try {
            //si está desabilitado el envio, lo enviamos como como transactional
            //a un correo test
            if (TRUE === $this->jangoMail->getConfig('disable_delivery')) {
                return $this->jangoMail->getTransactional()
                                ->setEmail($this->getEmail())->send();
            }
            $response = $this->jangoMail->getJangoInstance()
                    ->SendMassEmail($this->getParametersToSend());
            $response = preg_split('/\n/m', $response->SendMassEmailResult);

            if (0 == $response[0]) {
                $this->email->setEmailID($response[2]);
                $result = $this->email;
            } else {
                $this->jangoMail->setError("No se pudo enviar el Correo (Asunto: {$this->email->getSubject()})");
            }
        } catch (\Exception $e) {
            $this->jangoMail->setError($e->getMessage());
        }
        $this->jangoMail->addEmailLog($this->email, $result ? 'SUCCESS' : 'ERROR');
        return $result;
    }

}