<?php

namespace App\DataFixtures;

use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    use DataFixturesTrait;

    public function load(ObjectManager $manager)
    {
        $usersData = $this->loadData("users");
        foreach ($usersData as $userData) {
            $user = new User();
            $user->setEmail($userData["email"]);
            $user->setFirstName($userData["firstName"]);
            if(!empty($userData["subscriptions"])) {
                $subscription = new Subscription();
                foreach ($userData["subscriptions"] as $sub) {
                    $subscription->addNewsletter($this->getReference($sub));
                }
                $user->setSubscription($subscription);
            }
            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            NewsletterFixtures::class
        ];
    }
}
