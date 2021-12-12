<?php

namespace App\DataFixtures;

use App\Entity\Platform;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PlatformFixtures extends Fixture implements DependentFixtureInterface
{
    public const PLATFORM_REFERENCE = 'platform';

    public function load(ObjectManager $manager)
    {
        $platform = new Platform();

        $platform->setName('Platform');
        $platform->setPlatformBaseContent($this->getReference(PlatformBaseContentFixtures::PLATFORM_BASE_CONTENT_REFERENCE));

        $manager->persist($platform);
        $manager->flush();

        $this->addReference(self::PLATFORM_REFERENCE, $platform);
    }

    public function getDependencies()
    {
        return [
            PlatformBaseContentFixtures::class,
        ];
    }
}
