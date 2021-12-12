<?php

namespace App\DataFixtures;

use App\Entity\GameInfos;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GameInfosFixtures extends Fixture implements DependentFixtureInterface
{
    public const GAME_INFOS_REFERENCE = 'game-infos';

    public function load(ObjectManager $manager)
    {
        $gameInfos = new GameInfos();

        $gameInfos->setTitle('Title of Game');
        $gameInfos->addPlatform($this->getReference(PlatformFixtures::PLATFORM_REFERENCE));
        $gameInfos->addDeveloper($this->getReference(DeveloperFixtures::DEVELOPER_REFERENCE));
        $gameInfos->addPublisher($this->getReference(PublisherFixtures::PUBLISHER_REFERENCE));
        $gameInfos->setDescription("Description of this game with moult infos");

        $manager->persist($gameInfos);
        $manager->flush();

        $this->addReference(self::GAME_INFOS_REFERENCE, $gameInfos);
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
