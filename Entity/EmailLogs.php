<?php

namespace Netpeople\JangoMailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var object $email
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
     * @ORM\Column(name="error", type="string", length=255)
     */
    private $error;


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
     * Set email
     *
     * @param object $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return object 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set result
     *
     * @param string $result
     */
    public function setResult($result)
    {
        $this->result = $result;
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