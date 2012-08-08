<?php

namespace Netpeople\JangoMailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Netpeople\JangoMailBundle\Emails\Email;

/**
 * Netpeople\JangoMailBundle\Entity\EmailLogs
 *
 * @ORM\Table(name="email_logs")
 * @ORM\Entity(repositoryClass="Netpeople\JangoMailBundle\Entity\EmailLogsRepository")
 */
class EmailLogs
{

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Email
     *
     * @ORM\Column(name="email",  type="object")
     */
    private $email;

    /**
     * @var string $result
     *
     * @ORM\Column(name="result", type="string", length=50)
     */
    private $result;

    /**
     * @var string $error
     *
     * @ORM\Column(name="error", type="string", length=255, nullable=TRUE)
     */
    private $error;

    /**
     * @var string $error
     *
     * @ORM\Column(name="datetime", type="date", length=255)
     */
    private $datetime;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return Email 
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(Email $email)
    {
        $this->email = $email;
        return $this;
    }

    public function getDatetime()
    {
        return $this->datetime;
    }

    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
        return $this;
    }

    /**
     * Set result
     *
     * @param string $result
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * Get result
     *
     * @return string 
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set error
     *
     * @param string $error
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * Get error
     *
     * @return string 
     */
    public function getError()
    {
        return $this->error;
    }

}