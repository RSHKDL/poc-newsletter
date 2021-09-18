<?php

namespace App\Newsletter\Mailer;

use App\Entity\Newsletter;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class NewsletterMailer
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendNewsletter(User $user, Newsletter $newsletter): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address('newsletter@example.com'))
            ->to(new Address($user->getEmail()))
            ->subject($newsletter->getTitle())
            ->htmlTemplate("newsletter/email/base.html.twig")
            ->textTemplate("newsletter/email/base.txt.twig")
            ->context([
                "user" => $user,
                "newsletter" => $newsletter
            ])
        ;

        $this->mailer->send($email);
    }
}