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
    protected $error;

    public function __construct($config)
    {
        $this->setConfig($config);
    }

    public function getJangoInstance()
    {
        if (!($this->jangoClient instanceof \SoapClient)) {
            $this->jangoClient = new \SoapClient($this->config['url_jango']);
        }
        return $this->jangoClient;
    }

    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getGroupAdmin()
    {
        return $this->groupAdmin;
    }

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

    public function send(Emails\EmailTemplateInterface $email, Groups\Group $group)
    {
        return $this->getCampaign()->setEmail($email)
                        ->setGroup($group)->send();
    }

    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    public function getError()
    {
        return $this->error;
    }

}