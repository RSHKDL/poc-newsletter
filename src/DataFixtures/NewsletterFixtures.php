<?php

namespace App\DataFixtures;

use App\Entity\Newsletter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NewsletterFixtures extends Fixture
{
    use DataFixturesTrait;

    public function load(ObjectManager $manager)
    {
        $newslettersData = $this->loadData("newsletters");
        foreach ($newslettersData as $newsletterData) {
            $newsletter = new Newsletter();
            $newsletter->setTitle($newsletterData["title"]);
            $newsletter->setContent($newsletterData["content"]);
            $manager->persist($newsletter);
            $this->addReference($newsletter->getTitle(), $newsletter);
        }

        $manager->flush();
    }
}
