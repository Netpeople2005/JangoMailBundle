<?php

namespace Netpeople\JangoMailBundle\Reporting;

use DateTime;
use Netpeople\JangoMailBundle\Emails\EmailInterface;
use Netpeople\JangoMailBundle\Exception\JangoMailException;
use Netpeople\JangoMailBundle\JangoMail;
use SoapFault;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 * 
 * @see https://api.jangomail.com/
 */
class CampaignReporting
{

    /**
     *
     * @var JangoMail
     */
    protected $jango;

    function __construct(JangoMail $jango)
    {
        $this->jango = $jango;
    }

    /**
     * GetMassEmailReport
     * 
     * Gets the reporting statistics on a particular mass e-mail. Returns an array of integers.
     *  
     * @param \Netpeople\JangoMailBundle\Emails\EmailInterface $email
     * @return array
     * 
     *    array(
     *          'recipients' => (int),
     *          'opened' => (int),
     *          'clicked' => (int),
     *          'unsubscribes' => (int),
     *          'bounces' => (int),
     *          'forwards' => (int),
     *          'replies' => (int),
     *          'page_views' => (int),
     *    );
     * 
     * @throws JangoMailException
     */
    public function report(EmailInterface $email)
    {
        try {
            $result = $this->jango->call('GetMassEmailReport', array(
                'JobID' => $email->getEmailID()
            ));

            $result = $result->GetMassEmailReportResult->int;

            return array(
                'recipients' => $result[0],
                'opened' => $result[1],
                'clicked' => $result[2],
                'unsubscribes' => $result[3],
                'bounces' => $result[4],
                'forwards' => $result[5],
                'replies' => $result[6],
                'page_views' => $result[7],
            );
        } catch (SoapFault $e) {
            throw new JangoMailException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Reports_GetAllClicks_XML
     * 
     * Retrieves list of recipients that have clicked any link in a mass e-mail campaign. Returns an XML document.
     * 
     * @param \Netpeople\JangoMailBundle\Emails\EmailInterface $email
     * @param string $orderBy
     * @param string $sortOrder
     * @return array
     * 
     * array(
     *      array(
     *          'email' => (string),
     *           'url' => (string),
     *           'link_position' => (int),
     *           'click_date_time' => DateTime,
     *      ),
     *      ...
     * )
     * 
     * @throws JangoMailException
     */
    public function clicks(EmailInterface $email, $orderBy = Reporting::SORT_BY_EMAIL
    , $sortOrder = Reporting::SORT_ORDER_ASC)
    {
        try {
            $result = $this->jango->call('Reports_GetAllClicks_XML', array(
                'JobID' => $email->getEmailID(),
                'SortBy' => $orderBy,
                'SortOrder' => $sortOrder,
            ));

            $xml = $result->Reports_GetAllClicks_XMLResult->any;

            $recipients = array();

            foreach (simplexml_load_string($xml)->Clicks as $e) {
                $recipients[] = array(
                    'email' => (string) $e->EmailAddress,
                    'url' => (string) $e->URL,
                    'link_position' => (string) $e->LinkPosition,
                    'click_date_time' => new DateTime((string) $e->ClickDateTime),
                );
            }

            return $recipients;
        } catch (SoapFault $e) {
            throw new JangoMailException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Reports_GetOpens_XML
     * 
     * Retrieves list of recipients that have opened a mass e-mail campaign. Returns an XML document.
     * 
     * @param \Netpeople\JangoMailBundle\Emails\EmailInterface $email
     * @param string $orderBy
     * @param string $sortOrder
     * @param string $whitchTime
     * @return array
     * 
     * array(
     *      array(
     *          'email' => (string),
     *           'opens' => (int),
     *           'open_date_time' => DateTime,
     *      ),
     *      ...
     * )
     * 
     * @throws JangoMailException
     */
    public function opens(EmailInterface $email, $orderBy = Reporting::SORT_BY_EMAIL
    , $sortOrder = Reporting::SORT_ORDER_ASC, $whitchTime = Reporting::WHICH_TIME_FIRST)
    {
        try {
            $result = $this->jango->call('Reports_GetOpens_XML', array(
                'JobID' => $email->getEmailID(),
                'SortBy' => $orderBy,
                'SortOrder' => $sortOrder,
                'WhichTime' => $whitchTime,
            ));

            $xml = $result->Reports_GetOpens_XMLResult->any;

            $recipients = array();

            foreach (simplexml_load_string($xml)->Opens as $e) {
                $recipients[] = array(
                    'email' => (string) $e->EmailAddress,
                    'opens' => (string) $e->NumberOpens,
                    'open_date_time' => new DateTime((string) $e->OpenDateTime),
                );
            }

            return $recipients;
        } catch (SoapFault $e) {
            throw new JangoMailException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Reports_GetBouncesByCampaign_XML2
     * Retrieves list of bounced addresses for a particular mass e-mail campaign. Returns an XML document.
     * 
     * @param \Netpeople\JangoMailBundle\Emails\EmailInterface $email
     * @return array
     * 
     * array(
     *      array(
     *          'email' => (string),
     *           'smtp_code' => (string),
     *           'definitive' => (int),
     *           'bounce_date_time' => DateTime,
     *      ),
     *      ...
     * )
     * 
     * @throws JangoMailException
     */
    public function bounces(EmailInterface $email)
    {
        try {
            $result = $this->jango->call('Reports_GetBouncesByCampaign_XML2', array(
                'JobID' => $email->getEmailID(),
            ));

            $xml = $result->Reports_GetBouncesByCampaign_XML2Result->any;

            $recipients = array();

            foreach (simplexml_load_string($xml)->Bounces as $e) {
                $recipients[] = array(
                    'email' => (string) $e->EmailAddress,
                    'smtp_code' => (string) $e->SMTPCode,
                    'definitive' => (string) $e->Definitive,
                    'bounce_date_time' => new DateTime((string) $e->BounceDateTime),
                );
            }

            return $recipients;
        } catch (SoapFault $e) {
            throw new JangoMailException($e->getMessage(), $e->getCode(), $e);
        }
    }
    
    public function recipients(EmailInterface $email)
    {
        try {
            $result = $this->jango->call('Reports_GetRecipients_XML', array(
                'JobID' => $email->getEmailID(),
            ));

            $xml = $result->Reports_GetRecipients_XMLResult->any;

            $recipients = array();

            foreach (simplexml_load_string($xml)->Recipients as $e) {
                $recipients[] = (string) $e->EmailAddress;
            }

            return $recipients;
        } catch (SoapFault $e) {
            throw new JangoMailException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get de All Info by Email
     * 
     * @param \Netpeople\JangoMailBundle\Emails\EmailInterface $email
     * @param boolean $recipients true if return recipients data
     * @return array
     * 
     * array(
     *      'report' => (array),
     *      'opens' => (array),
     *      'clicks' => (array),
     *      'bounces' => (array),
     *      'recipients' => (array),
     * );
     * 
     */
    public function getAllInfo(EmailInterface $email, $recipients = false)
    {
        $result = array();

        $result['report'] = $this->report($email);
        $result['opens'] = $this->opens($email);
        $result['clicks'] = $this->clicks($email);
        $result['bounces'] = $this->bounces($email);
        $recipients && $result['recipients'] = $this->recipients($email);

        return $result;
    }

}
