<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Game;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class GameDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Game;
    }

    public function persist($data, array $context = [])
    {
        $data->setUpdatedAt(new DateTimeImmutable());
        // call your persistence layer to save $data
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        // If cover will be orphan, remove it
        if ($data->getCover() && 1 === count($data->getCover()->getGames())) {
            $this->entityManager->remove($data->getCover());
        }
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
