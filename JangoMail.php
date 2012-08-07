<?php

namespace Netpeople\JangoMailBundle;

use Netpeople\JangoMailBundle\Groups\GroupAdmin;
use Netpeople\JangoMailBundle\CampaignSending;
use Netpeople\JangoMailBundle\TransactionalSending;

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
     * @var GroupAdmin
     */
    protected $groupAdmin = NULL;

    /**
     *
     * @var CampaignSending 
     */
    protected $campaignSending = NULL;

    /**
     *
     * @var TransactionalSending 
     */
    protected $transactionalSending = NULL;

    /**
     *
     * @var \SoapClient
     */
    protected $jangoClient = NULL;

    /**
     *
     * @var string 
     */
    protected $error;

    public function __construct($config)
    {
        $this->setConfig($config);
    }

    /**
     *
     * @return \SoapClient 
     */
    public function getJangoInstance()
    {
        if (!($this->jangoClient instanceof \SoapClient)) {
            $this->jangoClient = new \SoapClient($this->config['url_jango']);
            //var_dump($this->jangoClient,$this->config['url_jango']);die;
        }
        return $this->jangoClient;
    }

    /**
     *
     * @param array $config
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
    public function getConfig($name = NULL)
    {
        if ($name) {
            return array_key_exists($name, $this->config) ?
                    $this->config[$name] : NULL;
        }
        return $this->config;
    }

    /**
     * 
     * @return GroupAdmin 
     */
    public function getGroupAdmin()
    {
        return $this->groupAdmin;
    }

    /**
     *
     * @param GroupAdmin $group
     * @return \Netpeople\JangoMailBundle\JangoMail 
     */
    public function setGroupAdmin(GroupAdmin $group)
    {
        $this->groupAdmin = $group;
        return $this;
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
        } elseif (count($email->getRecipients())) {
            return $this->getTransactional()->setEmail($email)->send();            
        }else{
            //aqui debemos informar que pasó
        }
    }

    /**
     *
     * @param string $error
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

}