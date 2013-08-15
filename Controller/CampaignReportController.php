<?php

namespace Netpeople\JangoMailBundle\Controller;

use DateTime;
use Netpeople\JangoMailBundle\Emails\Email;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of ReportController
 *
 * @author maguirre
 */
class CampaignReportController extends Controller
{

    /**
     * @ParamConverter("start")
     * @ParamConverter("end")
     */
    public function listAction(DateTime $start, DateTime $end)
    {
        $report = $this->get('jango_mail.campaign_reporting')
                ->emailsInfo($start, $end);

        return $this->render('JangoMailBundle:Report:list.html.twig', array(
                    'emails' => $report,
                    'days' => $start->diff($end)->d,
                    'start' => $start,
                    'end' => $end,
        ));
    }

    public function recipientsAction($id)
    {
        $recipients = $this->get('jango_mail.campaign_reporting')
                ->recipients(new Email($id));

        return $this->render('JangoMailBundle:Report:recipients.html.twig', array(
                    'recipients' => $recipients,
        ));
    }

    public function opensAction($id)
    {
        $recipients = $this->get('jango_mail.campaign_reporting')
                ->opens(new Email($id));

        return $this->render('JangoMailBundle:Report:opens.html.twig', array(
                    'recipients' => $recipients,
        ));
    }

}
