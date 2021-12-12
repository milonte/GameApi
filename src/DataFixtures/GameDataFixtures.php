<?php

namespace App\DataFixtures;

use App\Entity\gameData;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GameDataFixtures extends Fixture implements DependentFixtureInterface
{
    public const GAME_INFOS_REFERENCE = 'game-infos';

    public function load(ObjectManager $manager)
    {
        $gameData = new gameData();

        $gameData->setTitle('Title of Game');
        $gameData->addPlatform($this->getReference(PlatformFixtures::PLATFORM_REFERENCE));
        $gameData->addDeveloper($this->getReference(DeveloperFixtures::DEVELOPER_REFERENCE));
        $gameData->addPublisher($this->getReference(PublisherFixtures::PUBLISHER_REFERENCE));
        $gameData->setDescription("Description of this game with moult infos");

        $manager->persist($gameData);
        $manager->flush();

        $this->addReference(self::GAME_INFOS_REFERENCE, $gameData);
    }

    public function getDependencies()
    {
        return [
            PlatformFixtures::class,
            DeveloperFixtures::class,
            PublisherFixtures::class,
        ];
    }
}
