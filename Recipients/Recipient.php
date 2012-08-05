<?php

namespace Netpeople\JangoMailBundle\Recipients;

use Netpeople\JangoMailBundle\Recipients\RecipientInterface;

/**
 * Description of Recipient
 *
 * @author manuel
 */
class Recipient implements RecipientInterface
{

    protected $email;
    protected $name;
    protected $group;
    
    public function __construct($email = NULL,$name = NULL)
    {
        $this->setEmail($email)->setname($name);
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function setname($name)
    {
        $this->name = $name;
        return $this;
    }

    //put your code here
    public function getEmail()
    {
        return $this->email;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setGroup(\Netpeople\JangoMailBundle\Groups\Group $group)
    {
        
        $this->group = $group;
        return $this;
    }

    public function getGroup()
    {
        return $this->group;
    }

}