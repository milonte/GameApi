<?php

namespace App\DataFixtures;

use App\Entity\PlatformBaseContent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PlatformBaseContentFixtures extends Fixture implements DependentFixtureInterface
{
    public const PLATFORM_BASE_CONTENT_REFERENCE = "platform-base-content-reference";

    public function load(ObjectManager $manager): void
    {
        $platformBaseContent = new PlatformBaseContent();

        $platformBaseContent->setPhysicalSupport($this->getReference(PhysicalSupportFixtures::PHYSICAL_SUPPORT_REFERENCE));
        $platformBaseContent->setPhysicalContainer($this->getReference(PhysicalContainerFixtures::PHYSICAL_CONTAINER_REFERENCE));
        $platformBaseContent->addPhysicalContent($this->getReference(PhysicalContentFixtures::PHYSICAL_CONTENT_REFERENCE));

        $manager->persist($platformBaseContent);
        $manager->flush();

        $this->addReference(self::PLATFORM_BASE_CONTENT_REFERENCE, $platformBaseContent);
    }

    public function getDependencies()
    {
        return [
            PhysicalSupportFixtures::class,
            PhysicalContainerFixtures::class,
            PhysicalContentFixtures::class,
        ];
    }
}
