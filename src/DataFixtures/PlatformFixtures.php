<?php

namespace App\DataFixtures;

use App\Entity\Platform;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PlatformFixtures extends Fixture
{
    public const PLATFORM_REFERENCE = 'platform';

    public function load(ObjectManager $manager)
    {
        $platform = new Platform();

        $platform->setName('Platform');

        $manager->persist($platform);
        $manager->flush();

        $this->addReference(self::PLATFORM_REFERENCE, $platform);
    }
}
