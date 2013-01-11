<?php

namespace Netpeople\JangoMailBundle;

use Netpeople\JangoMailBundle\Emails\EmailInterface;
use Netpeople\JangoMailBundle\Exception\CampaignException;
use Netpeople\JangoMailBundle\AbstractSending;

/**
 * Description of CampaignSending
 *
 * @author manuel
 */
class CampaignSending extends AbstractSending
{

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
            'MessagePlain' => strip_tags($this->email->getMessage()),
            'MessageHTML' => $this->email->getMessage(),
            'Options' => $this->getOptionsString(),
        );
    }

    public function send()
    {
        $result = FALSE;
        if (!($this->email instanceof EmailInterface)) {
            throw new CampaignException('Debe llamar a setEmail() antes de hacer el Envío');
        }
        if (!count($this->email->getGroups())) {
            throw new CampaignException('Debe agregar al menos un grupo antes de hacer el Envío');
        }
        try {
            //si está desabilitado el envio, lo enviamos como transactional
            //a un correo test
            if (TRUE === $this->jangoMail->getConfig('disable_delivery')) {
                return $this->jangoMail->getTransactional()
                                ->setEmail($this->email)->send();
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