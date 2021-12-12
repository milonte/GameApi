<?php

namespace App\DataFixtures;

use App\Entity\PhysicalContent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PhysicalContentFixtures extends Fixture
{
    public const PHYSICAL_CONTENT_REFERENCE = "physical-content-reference";

    public function load(ObjectManager $manager): void
    {

        $physicalContent = new PhysicalContent();

        $physicalContent->setName("Book");

        $manager->persist($physicalContent);
        $manager->flush();

        $this->addReference(self::PHYSICAL_CONTENT_REFERENCE, $physicalContent);
    }
}
