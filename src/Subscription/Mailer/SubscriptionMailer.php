<?php

namespace App\Subscription\Mailer;

use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class SubscriptionMailer
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendSubscriptionConfirmationEmail(User $user, Collection $newsletters): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address('hello@example.com'))
            ->to(new Address($user->getEmail()))
            ->subject('Newsletter subscription confirmation')
            ->htmlTemplate("subscription/email/confirmation.html.twig")
            ->textTemplate("subscription/email/confirmation.txt.twig")
            ->context([
                "user" => $user,
                "count" => $newsletters->count()
            ])
        ;

        $this->mailer->send($email);
    }
}