<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    use DataFixturesTrait;

    public function load(ObjectManager $manager)
    {
        $usersData = $this->loadData("users");
        foreach ($usersData as $userData) {
            //todo
        }

        $manager->flush();
    }
}
