<?php

namespace App\DataFixtures;

use App\Entity\Developer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DeveloperFixtures extends Fixture
{

    public const DEVELOPER_REFERENCE = 'developer';

    public function load(ObjectManager $manager): void
    {
        $developer = new Developer();

        $developer->setName('Developer ');

        $manager->persist($developer);
        $manager->flush();

        $this->addReference(self::DEVELOPER_REFERENCE, $developer);

    }

}
