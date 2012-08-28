<?php

namespace Netpeople\JangoMailBundle;

use Netpeople\JangoMailBundle\Emails\EmailInterface;
use Netpeople\JangoMailBundle\JangoMail;
use Netpeople\JangoMailBundle\Groups\Group;
use Netpeople\JangoMailBundle\Recipients\Recipient;

/**
 * Description of CampaignSending
 *
 * @author manuel
 */
class TransactionalSending
{

    /**
     *
     * @var JangoMail 
     */
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

    public function getParametersToSend(Recipient $recipient)
    {
        $config = $this->jangoMail->getConfig();

        return array(
            'Username' => $config['username'],
            'Password' => $config['password'],
            'FromEmail' => $config['fromemail'],
            'FromName' => $config['fromname'],
            'ToEmailAddress' => $recipient->getEmail(),
            'Subject' => $this->email->getSubject(),
            'MessagePlain' => strip_tags($this->email->getMessage()),
            'MessageHTML' => $this->email->getMessage(),
            'Options' => $this->jangoMail->getOptionsString(array(
                'BCC' => join(';', $config['bcc'])
            )),
        );
    }

    public function send()
    {
        $result = FALSE;
        if (!($this->email instanceof EmailInterface)) {
            throw new \Exception('Debe llamar a setEmail() antes de hacer el Envío');
        }
        //si está desabilitado el envio, quitamos los destinatarios
        //y agregamos un test
        if (TRUE === $this->jangoMail->getConfig('disable_delivery')) {
            //si hay correos de prueba definidos en el config.yml
            //colocamos a uno de ellos como destinatario.
            if (count($this->jangoMail->getConfig('bcc'))) {
                $this->email->getRecipients()->clear();
                $recipient = new Recipient(current($this->jangoMail->getConfig('bcc')));
                $this->email->getRecipients()->add($recipient);
            } else {
                //si no hay a quien enviar, no lo enviamos.
                $this->email->setEmailID('- TEST -');
                $result = $this->email;
            }
        } else {
            if (!count($this->email->getRecipients())) {
                throw new \Exception('Debe especificar al menos un Recipient antes de hacer el Envío');
            }
            try {
                foreach ($this->email->getRecipients() as $recipient) {
                    $response = $this->jangoMail->getJangoInstance()
                            ->SendTransactionalEmail($this->getParametersToSend($recipient));
                }
                $response = preg_split('/\n/m', $response->SendTransactionalEmailResult);

                if (0 == $response[0]) {
                    $this->email->setEmailID($response[2]);
                    $result = $this->email;
                } else {
                    $this->jangoMail->setError("No se pudo enviar el Correo (Asunto: {$this->email->getSubject()})");
                }
            } catch (\Exception $e) {
                $this->jangoMail->setError($e->getMessage());
            }
        }
        $this->jangoMail->addEmailLog($this->email, $result ? 'SUCCESS' : 'ERROR');
        return $result;
    }

}