<?php

namespace App\DataFixtures;

use App\Entity\PhysicalContainer;
use App\Entity\PlatformBaseContent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PhysicalContainerFixtures extends Fixture
{

    public const PHYSICAL_CONTAINER_REFERENCE = "physical-container-reference";

    public function load(ObjectManager $manager): void
    {
        foreach (PhysicalContainer::PHYSICAL_CONTAINERS as $container) {

            $physicalContainer = new PhysicalContainer();

            $physicalContainer->setName($container);

            $manager->persist($physicalContainer);
            $manager->flush();
        }

        $this->addReference(self::PHYSICAL_CONTAINER_REFERENCE, $physicalContainer);
    }
}
