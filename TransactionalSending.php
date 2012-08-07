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

    /**
     *
     * @var boolean 
     */
    private $isTEST = FALSE;

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
            'ToEmailAddress' => $$recipient->getEmail(),
            'Subject' => $this->email->getSubject(),
            'MessagePlain' => $this->email->getMessagePlain(),
            'MessageHTML' => $this->email->getMessageHtml(),
            'Options' => $this->email->getOptionsString(),
        );
    }

    public function send()
    {
        if (!($this->getEmail() instanceof EmailInterface)) {
            throw new \Exception('Debe llamar a setEmail() antes de hacer el Envío');
        }
        if (!count($this->getEmail()->getRecipients())) {
            throw new \Exception('Debe especificar al menos un Recipient antes de hacer el Envío');
        }
        try {
            //verificamos que se pueda mandar el correo
            if (FALSE === $this->jangoMail->getConfig('disable_delivery') || $this->isTEST) {
                $this->isTEST = FALSE;
                foreach ($this->getEmail()->getRecipients() as $recipient) {
                    $response = $this->jangoMail->getJangoInstance()
                            ->SendTransactionalEmail($this->getParametersToSend($recipient));
                }
                $response = explode('\n', $response->SendTransactionalEmailResult);
            } else {
                //si está desabilitado el envio, retormanos una respuesta de prueba :-)
                $response = array(0, 'SUCCESS', '-TEST-');
            }

            if (0 == $response[0]) {
                $this->email->setEmailID($response[2]);

                $emailResult = $this->email;

                //enviamos el correo a los emails de prueba
                $this->sendToTesters(clone $this->getEmail());

                return $emailResult;
            } else {
                $this->jangoMail->setError("No se pudo enviar el Correo (Asunto: {$this->email->getSubject()})");
            }
        } catch (\Exception $e) {
            $this->jangoMail->setError($e->getMessage());
        }
        $this->isTEST = FALSE;
        return FALSE;
    }

    /**
     *
     * @param Emails\EmailInterface $email
     * @return boolean 
     */
    public function sendToTesters(EmailInterface $email)
    {
        if (count($this->jangoMail->getConfig('emails_bcc'))) {
            $email->setRecipients(array());
            foreach ($this->jangoMail->getConfig('emails_bcc') as $bcc) {
                $email->addRecipient(new Recipient($bcc));
            }
            $this->isTEST = TRUE;
            $this->setEmail($email)->send();
        }
    }

}