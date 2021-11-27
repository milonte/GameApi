<?php

namespace App\DataFixtures;

use App\Entity\Publisher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PublisherFixtures extends Fixture
{

    public const PUBLISHER_REFERENCE = 'publisher';

    public function load(ObjectManager $manager): void
    {
        $publisher = new Publisher();

        $publisher->setName('Publisher ');

        $manager->persist($publisher);
        $manager->flush();

        $this->addReference(self::PUBLISHER_REFERENCE, $publisher);

    }
}
