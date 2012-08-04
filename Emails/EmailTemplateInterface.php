<?php

namespace Netpeople\JangoMailBundle\Emails;

/**
 * Description of EmailTemplateInterface
 *
 * @author manuel
 */
interface EmailTemplateInterface
{

    public function getSubject();

    public function getMessageHtml();

    public function getMessagePlain();

    public function getOptions();
}
