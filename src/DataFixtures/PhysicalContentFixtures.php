<?php

namespace App\DataFixtures;

use App\Entity\PhysicalContent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PhysicalContentFixtures extends Fixture
{
    public const PHYSICAL_CONTENT_REFERENCE = "physical-content-reference";
    private const PHYSICAL_CONTENTS = [
        "Cover",
        "Book"
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PHYSICAL_CONTENTS as $content) {
            $physicalContent = new PhysicalContent();

            $physicalContent->setName($content);

            $manager->persist($physicalContent);
            $manager->flush();
        }

        $this->addReference(self::PHYSICAL_CONTENT_REFERENCE, $physicalContent);
    }
}
