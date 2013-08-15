<?php

namespace Netpeople\JangoMailBundle\Controller;

use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of ReportController
 *
 * @author maguirre
 */
class ReportController extends Controller
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

}
