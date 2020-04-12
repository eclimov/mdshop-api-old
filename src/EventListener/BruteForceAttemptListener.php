<?php

namespace App\EventListener;
use Anyx\LoginGateBundle\Event\BruteForceAttemptEvent;
use Swift_Mailer;
use Swift_Message;

class BruteForceAttemptListener
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $mailfrom;

    /**
     * @var string
     */
    private $mailto;

    public function __construct(Swift_Mailer $mailer, string $mailfrom, string $mailto)
    {
        $this->mailer = $mailer;
        $this->mailfrom = $mailfrom;
        $this->mailto = $mailto;
    }

    public function onBruteForceAttempt(BruteForceAttemptEvent $event)
    {
        $req = $event->getRequestEvent()->getRequest();

        // TODO: use TWIG to render templates
        $body = '<b>Attempted to log in via brute-force attack</b><hr>';
        foreach ($req->getClientIps() as $ip) {
            $body .= '<b>IP:</b> ' . $ip;
            $body .= '<br/>';
            $body .= '<b>Request:</b> ' . $req->getContent();
            $body .= '<br/>';
        }

        $message = (new Swift_Message())
            ->setSubject('Brute-force attack')
            ->setFrom([$this->mailfrom])
            ->setTo([$this->mailto])
            ->setBody(
                $body,
                'text/html'
            )
        ;

        $this->mailer->send($message);
    }
}
