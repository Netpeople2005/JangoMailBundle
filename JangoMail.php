<?php

namespace Netpeople\JangoMailBundle;

use DateTime;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Netpeople\JangoMailBundle\CampaignSending;
use Netpeople\JangoMailBundle\Emails\EmailInterface;
use Netpeople\JangoMailBundle\Entity\EmailLogs;
use Netpeople\JangoMailBundle\Exception\JangoMailException;
use Netpeople\JangoMailBundle\TransactionalSending;
use Psr\Log\LoggerInterface;
use SoapClient;

/**
 * Description of JangoMail
 *
 * @author manuel
 */
class JangoMail
{

    /**
     *
     * @var array
     */
    protected $config = array();

    /**
     *
     * @var CampaignSending
     */
    protected $campaignSending;

    /**
     *
     * @var TransactionalSending
     */
    protected $transactionalSending;

    /**
     *
     * @var SoapClient
     */
    protected $jangoClient;

    /**
     *
     * @var string
     */
    protected $error;

    /**
     *
     * @var Registry
     */
    protected $registry;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct($config, Registry $registry, LoggerInterface $logger = null)
    {
        $this->setConfig($config)->registry = $registry;
        $this->logger = $logger;
    }

    /**
     *
     * @return SoapClient
     */
    public function getJangoInstance()
    {
        if (!($this->jangoClient instanceof SoapClient)) {
            $this->jangoClient = new SoapClient($this->config['url_jango']);
            //var_dump($this->jangoClient,$this->config['url_jango']);die;
        }

        return $this->jangoClient;
    }

    /**
     *
     * @return EntityManager
     */
    public function getManager()
    {
        return $this->registry->getManager();
    }

    /**
     *
     * @param array $config
     *
     * @return \Netpeople\JangoMailBundle\JangoMail
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     *
     * @return array
     */
    public function getConfig($name = null)
    {
        if ($name) {
            return array_key_exists($name, $this->config) ?
                $this->config[$name] : null;
        }

        return $this->config;
    }

    /**
     *
     * @return CampaignSending
     */
    public function getCampaign()
    {
        return $this->campaignSending;
    }

    /**
     *
     * @param CampaignSending $campaign
     *
     * @return \Netpeople\JangoMailBundle\JangoMail
     */
    public function setCampaign(CampaignSending $campaign)
    {
        $this->campaignSending = $campaign;

        return $this;
    }

    /**
     *
     * @return TransactionalSending
     */
    public function getTransactional()
    {
        return $this->transactionalSending;
    }

    public function setTransactional(TransactionalSending $transactional)
    {
        $this->transactionalSending = $transactional;

        return $this;
    }

    /**
     *
     * @param Emails\EmailInterface $email
     *
     * @return boolean
     */
    public function send(Emails\EmailInterface $email)
    {
        /*
         * primero verificamos si tiene grupos establecidos, si es así
         * enviamos el correo como campaña
         */
        if (count($email->getGroups())) {
            return $this->getCampaign()->setEmail($email)->send();
        }
        /*
         * Si no tiene grupos y tiene recipientes, enviamos el correo como transactional
         */
        if (count($email->getRecipients())) {
            return $this->getTransactional()->setEmail($email)->send();
        }
        /*
         * Si llegamos acá es porque el email no tiene ni grupos ni recipientes.
         */
        throw new JangoMailException("Estas Intentando enviar un correo que no tiene ni grupos ni recipientes asignados.");
    }

    /**
     *
     * @param string $error
     *
     * @return \Netpeople\JangoMailBundle\JangoMail
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    public function addEmailLog(EmailInterface $email, $result)
    {
        if ($this->getConfig('enable_log') == true) {
            $log = new EmailLogs();
            $log->setEmail($email)->setResult($result)
                ->setError($this->getError())
                ->setDatetime(new DateTime('now'));
            $eManager = $this->getManager();
            $eManager->persist($log);
            $eManager->flush();
        }

        return $this;
    }

    public function getOptionsString(EmailInterface $email, array $options = array())
    {
        $options = ((array) $email->getOptions()) + $options;
        $opts = array();
        foreach ($options as $index => $value) {
            if ($value) {
                $opts[] = "$index=$value";
            }
        }

        return join(',', $opts);
    }

    public function prepare(array $arguments = array())
    {
        return array(
            'Username' => $this->getConfig('username'),
            'Password' => $this->getConfig('password'),
        ) + $arguments;
    }

    public function call($method, array $arguments = array())
    {
        if ($this->logger) {
            $this->logger->debug(sprintf("[Jango] Calling to %s", $method), $arguments);
        }

        try {
            $result = $this->getJangoInstance()
                ->{$method}($this->prepare($arguments));
        } catch (\Exception $e) {

            if ($this->logger) {
                $this->logger->error(sprintf("[Jango ERROR] Calling to %s, with errror: %s"
                    , $method, $e->getMessage()), $arguments);
            }

            throw $e;
        }

        return $result;
    }

}