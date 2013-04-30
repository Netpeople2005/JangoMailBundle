<?php

namespace Netpeople\JangoMailBundle;

use Netpeople\JangoMailBundle\JangoMail;
use FOS\UserBundle\Mailer\TwigSwiftMailer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Netpeople\JangoMailBundle\Emails\Email;
use Netpeople\JangoMailBundle\Exception\JangoMailException;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * Description of JangoMailFosUser
 *
 * @author maguirre
 */
class JangoMailFosUser extends TwigSwiftMailer
{

    /**
     *
     * @var JangoMail
     */
    protected $jango;

    /**
     *
     * @var LoggerInterface
     */
    protected $logger;

    function __construct(JangoMail $jango, UrlGeneratorInterface $router, \Twig_Environment $twig, array $parameters, LoggerInterface $logger = null)
    {
        $this->jango = $jango;
        $this->router = $router;
        $this->twig = $twig;
        $this->parameters = $parameters;
    }

    /**
     * @param string $templateName
     * @param array  $context
     * @param string $fromEmail
     * @param string $toEmail
     */
    protected function sendMessage($templateName, $context, $fromEmail, $toEmail)
    {
        $template = $this->twig->loadTemplate($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $email = new Email();

        $email->addRecipient($toEmail)
                ->setSubject($subject)
                ->setMessage($htmlBody ? : $textBody);

        try {
            $this->jango->send($message);
        } catch (JangoMailException $e) {
            if($this->logger){
                $this->logger->error($e->getMessage());
            }
        }
    }

}
