<?php

namespace App\Event\Subscriber;

use App\Entity\User;
use App\Event\Constants\EmailEvents;
use App\Event\EmailEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EmailSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $sender;
    private $router;
    
    public function __construct(\Swift_Mailer $mailer, $sender, UrlGeneratorInterface $router)
    {
        $this->mailer = $mailer;
        $this->sender = $sender;
        $this->router = $router;
    }
    
    public static function getSubscribedEvents(): array
    {
        return [
            EmailEvents::FORGOT_PASSWORD => 'onUserRequestPassword',
            EmailEvents::USER_REGISTERED => 'onUserRegistrated',
        ];
    }
    
    public function onUserRequestPassword(EmailEvent $event): void
    { 
        $user = $event->getUser();
       
        $subject = 'Réinitialisation du mot de passe';
        $body = 'Pour rénitialisé votre mot de passe cliqué sur le lien suivant : ';
//            $this->router->generate(
//                'security_resetPassword',
//                ['token' => $user->getResetToken()],
//                UrlGeneratorInterface::ABSOLUTE_URL
//            );
        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setTo($user->getEmail())
            ->setFrom($this->sender)
            ->setBody($body, 'text/html')
        ; 
        $this->mailer->send($message);
    }
    
    public function onUserRegistrated(EmailEvent $event): void
    {
        /** @var User $user */
        $user = $event->getSubject();
        $subject = 'Confirmer votre inscription';
        $body = 'Votre inscription à bien été prise en compte'.
            ' mais vous devez encore la confirmer en cliquant sur le lien suivant : '.
            $this->router->generate(
                'confirm_user',
                ['token' => $user->getValidationToken()],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setTo($user->getEmail())
            ->setFrom($this->sender)
            ->setBody($body, 'text/html')
        ;
        $this->mailer->send($message);
    }
}