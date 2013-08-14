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
     * @see http://api.jangomail.com/help/html/fff2cda4-cf49-d2a5-864f-92f3d936f25d.htm
     * 
     */
    public function report(EmailInterface $email)
    {
        try {
            $result = $this->jango->call('GetMassEmailReport', array(
                'JobID' => $email->getEmailID()
            ));

            $result = $result->GetMassEmailReportResult->int;

            return new ParameterBag(array(
                'recipients' => $result[0],
                'opened' => $result[1],
                'clicked' => $result[2],
                'unsubscribes' => $result[3],
                'bounces' => $result[4],
                'forwards' => $result[5],
                'replies' => $result[6],
                'page_views' => $result[7],
            ));
        } catch (SoapFault $e) {
            throw new JangoMailException($e->getMessage(), $e->getCode(), $e);
        }
    }

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
                    'click_date_time_string' => (string) $e->ClickDateTime,
                );
            }

            return $recipients;
        } catch (SoapFault $e) {
            throw new JangoMailException($e->getMessage(), $e->getCode(), $e);
        }
    }

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
            var_dump(simplexml_load_string($xml));die;
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

}
