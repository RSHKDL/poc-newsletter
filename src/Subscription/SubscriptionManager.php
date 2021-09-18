<?php

namespace App\Subscription;

use App\Subscription\Dto\Subscription as SubscriptionDto;
use App\Entity\Subscription;
use App\Entity\User;
use App\Repository\NewsletterRepository;
use App\Repository\UserRepository;
use App\Subscription\Mailer\SubscriptionMailer;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\ORMException;

class SubscriptionManager
{
    private UserRepository $userRepository;
    private NewsletterRepository $newsletterRepository;
    private SubscriptionMailer $subscriptionMailer;

    public function __construct(
        UserRepository $userRepository,
        NewsletterRepository $newsletterRepository,
        SubscriptionMailer $subscriptionMailer
    ) {
        $this->userRepository = $userRepository;
        $this->newsletterRepository = $newsletterRepository;
        $this->subscriptionMailer = $subscriptionMailer;
    }

    public function manageSubscription(SubscriptionDto $newSubscription): void
    {
        try {
            $user = $this->userRepository->findOneBy(["email" => $newSubscription->email]);
            if (null === $user) {
                $user = $this->createUserFromSubscription($newSubscription);
            }
            $newsletters = $this->updateSubscription($user, $newSubscription->newsletters);
            $this->subscriptionMailer->sendSubscriptionConfirmationEmail($user, $newsletters);
        } catch (\Throwable $throwable) {
            dd($throwable);
            // maybe log errors here
        }

    }

    public function unsubscribe(string $userUuid, string $newsletterUuid): string
    {
        try {
            $user = $this->userRepository->findOneBy(["uuid" => $userUuid]);
            $newsletter = $this->newsletterRepository->findOneBy(["uuid" => $newsletterUuid]);
            $subscription = $user->getSubscription();
            $subscription->removeNewsletter($newsletter);
            $user->setSubscription($subscription);
            $this->userRepository->save($user, true);
        } catch (\Throwable $throwable) {
            dd($throwable);
            // maybe log errors here
        }

        return $newsletter->getTitle();
    }

    /**
     * @throws ORMException
     */
    private function createUserFromSubscription(SubscriptionDto $subscription): User
    {
        $user = new User();
        $user->setEmail($subscription->email);
        $user->setFirstName($subscription->firstName);

        $this->userRepository->save($user);

        return $user;
    }

    /**
     * Only update subscriptions. If user already subscribed to newsletter 1 and 3 and choose to subscribe to 1 and 2,
     * his subscription will contain newsletter 1, 2 and 3. To unsubscribe, he should do it manually.
     * @see unsubscribe()
     *
     * @throws ORMException
     */
    private function updateSubscription(User $user, Collection $newsletters): Collection
    {
        if ($user->hasSubscription()) {
            $userSubscription = $user->getSubscription();
        } else {
            $userSubscription = new Subscription();
        }

        foreach ($newsletters as $newsletter) {
            $userSubscription->addNewsletter($newsletter);
        }

        $user->setSubscription($userSubscription);
        $this->userRepository->save($user, true);

        return $newsletters;
    }
}