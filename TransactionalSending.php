<?php

namespace Netpeople\JangoMailBundle;

use Netpeople\JangoMailBundle\Emails\EmailInterface;
use Netpeople\JangoMailBundle\Recipients\Recipient;
use Netpeople\JangoMailBundle\Exception\TransactionalException;
use Netpeople\JangoMailBundle\AbstractSending;

/**
 * Description of CampaignSending
 *
 * @author manuel
 */
class TransactionalSending extends AbstractSending
{

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
            'Options' => $this->getOptionsString(),
        );
    }

    public function send()
    {
        $result = FALSE;
        if (!($this->email instanceof EmailInterface)) {
            throw new TransactionalException('Debe llamar a setEmail() antes de hacer el Envío');
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
                return $this->_send();
            } else {
                //si no hay a quien enviar, no lo enviamos y lo devolvemos.
                $this->email->setEmailID('- TEST -');
                //establesco las opciones para el email.
                $this->email->setOptions($this->prepareOptions());
                $this->jangoMail->addEmailLog($this->email, 'SUCCESS');
                return $this->email;
            }
        } else {
            //si está habilitado lo enviamos
            return $this->_send();
        }
    }

    protected function _send()
    {
        $result = FALSE;
        if (!count($this->email->getRecipients())) {
            throw new TransactionalException('Debe especificar al menos un Recipient antes de hacer el Envío');
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
        $this->jangoMail->addEmailLog($this->email, $result ? 'SUCCESS' : 'ERROR');
        return $result;
    }

    public function getValidOptions()
    {
        return array(
            'ReplyTo' => null,
            'BCC' => null,
            'CharacterSet' => null,
            'Encoding' => null,
            'Priority' => null,
            'NoDuplicates' => null,
            'UseSystemMAILFROM' => null,
            'Receipt' => null,
            'Wrapping' => null,
            'ClickTrack' => null,
            'OpenTrack' => null,
            'NoClickTrackText' => null,
            'SendDate' => null,
            'ThrottlingNumberEmails' => null,
            'ThrottlingNumberMinutes' => null,
            'DoNotSendTo' => null,
            'SuppressionGroups' => null,
            'Triggers' => null,
            'EmbedImages' => null,
            'Attachment1' => null,
            'Attachment2' => null,
            'Attachment3' => null,
            'Attachment4' => null,
            'Attachment5' => null,
            'SMS' => null,
            'Template' => null,
            'CustomCampaignID' => null,
            'PreprocessNow' => null,
            'PreprocessOnly' => null,
            'TransactionalGroupID' => null,
            'CC' => null,
            'SkipUnsubCheck' => null,
        );
    }

}