<?php

namespace App\DataFixtures;

use App\Entity\Game;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GameFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $i = 0;

        do {
            $game = new Game();

            $game->setIsbn('2765410054');
            $game->setGameData($this->getReference(GameDataFixtures::GAME_INFOS_REFERENCE));
            $game->addDeveloper($this->getReference(DeveloperFixtures::DEVELOPER_REFERENCE));
            $game->setPlatform($this->getReference(PlatformFixtures::PLATFORM_REFERENCE));
            $game->addPublisher($this->getReference(PublisherFixtures::PUBLISHER_REFERENCE));
            $game->setReleaseDate(new DateTimeImmutable());
            $game->setCover($this->getReference(CoverObjectFixtures::COVER_OBJECT_REFERENCE));

            $manager->persist($game);
            $manager->flush();
            $i++;
        } while ($i < 10);
    }

    public function getDependencies()
    {
        return [
            GameDataFixtures::class,
            PlatformFixtures::class,
            DeveloperFixtures::class,
            PublisherFixtures::class,
            CoverObjectFixtures::class,
        ];
    }
}
