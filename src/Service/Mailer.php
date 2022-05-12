<?php

namespace App\Service;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class Mailer
{
    protected $mailer;
    protected $router;
    protected $twig;
    protected $logger;
    protected $noreply;

    /**
     * Mailer constructor.
     * @param \Swift_Mailer $mailer
     * @param RouterInterface $router
     * @param Environment $twig
     * @param LoggerInterface $logger
     * @param string $noreply
     */
    public function __construct(\Swift_Mailer $mailer, RouterInterface $router, Environment $twig, LoggerInterface $logger, string $noreply)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
        $this->logger = $logger;
        $this->noreply = $noreply;
    }

    /**
     * @param User $user
     * @throws \Exception
     * @throws \Throwable
     */
    public function sendActivationEmailMessage(User $user)
    {
        $mailLogger = new \Swift_Plugins_Loggers_ArrayLogger();
        $this->mailer->registerPlugin(new \Swift_Plugins_LoggerPlugin($mailLogger));

        $url = $this->router->generate('activate', ['token' => $user->getToken()], UrlGeneratorInterface::ABSOLUTE_URL);

        $context = [
            'user' => $user,
            'activationUrl' => $url
        ];

        $this->sendMessage('email/register-done.html.twig', $context, $this->noreply, $user->getEmail());
    }

    /**
     * @param User $user
     * @throws \Exception
     * @throws \Throwable
     */
    public function sendResetPasswordEmailMessage(User $user)
    {
        $url = $this->router->generate('reset_password', ['token' => $user->getToken()], UrlGeneratorInterface::ABSOLUTE_URL);

        $messageBody = [
            'user' => $user,
            'resetPasswordUrl' => $url,
        ];

        $this->sendMessage('email/request-password.html.twig', $messageBody, $this->noreply, $user->getEmail());
    }

    /**
     * @param $templateName string
     * @param $context array
     * @param $fromEmail string
     * @param $toEmail string
     * @return bool
     * @throws \Throwable
     * @throws \Exception
     */
    protected function sendMessage($templateName, $context, $fromEmail, $toEmail)
    {

        $context = $this->twig->mergeGlobals($context);
        $template = $this->twig->load($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail);

        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }

        try {
            $result = $this->mailer->send($message);
        } catch (TransportExceptionInterface $e) {
            // some error prevented the email sending; display an
            // error message or try to resend the message
        }
        #dd($this->mailer);
        $log_context = ['to' => $toEmail, 'message' => $textBody, 'template' => $templateName];
        if ($result) {
            $this->logger->info('SMTP email sent', $log_context);
        } else {
            $this->logger->error('SMTP email error', $log_context);
        }

        return $result;
    }
}
