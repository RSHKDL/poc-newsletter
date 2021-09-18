<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NewsletterFixtures extends Fixture
{
    use DataFixturesTrait;

    public function load(ObjectManager $manager)
    {
        $newslettersData = $this->loadData("newsletters");
        foreach ($newslettersData as $newsletterData) {
            // todo
        }

        $manager->flush();
    }
}
