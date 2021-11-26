<?php

namespace App\Controller;

use App\Entity\MediaObject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
final class CreateMediaObjectAction extends AbstractController
{
    public function __invoke(EntityManagerInterface $manager, Request $request): MediaObject
    {
        // Throw Exception if no file uploaded, or forgot the 'file' key
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        // Throw Exception if no slug, or forgot the 'slug' key
        if (!$request->get('slug')) {
            throw new BadRequestHttpException('"slug" is required');
        }

        $slug = "cover_" . $request->get('slug');

        /* check if image with this slug already exists
        and update it, or create new one */
        $fileAlreadyExist = $manager->getRepository(MediaObject::class)
            ->findOneBySlug($slug);

        if (null === $fileAlreadyExist) {
            $mediaObject = new MediaObject();
        } else {
            $mediaObject = $fileAlreadyExist;
        }

        $mediaObject->file = $uploadedFile;

        $mediaObject->setSlug($slug);

        // need this to call Doctrine if the file is updated
        $mediaObject->setUpdatedAt();

        return $mediaObject;
    }
}
