<?php

namespace App\DataFixtures;

use App\Entity\PhysicalSupport;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PhysicalSupportFixtures extends Fixture
{
    public const PHYSICAL_SUPPORT_REFERENCE = "physical-support-reference";

    public function load(ObjectManager $manager): void
    {
        foreach (PhysicalSupport::PHYSICAL_SUPPORTS as $support) {

            $physicalSupport = new PhysicalSupport();

            $physicalSupport->setName($support);

            $manager->persist($physicalSupport);
            $manager->flush();
        }

        $this->addReference(self::PHYSICAL_SUPPORT_REFERENCE, $physicalSupport);
    }
}
