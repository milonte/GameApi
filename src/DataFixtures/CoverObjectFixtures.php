<?php

namespace App\DataFixtures;

use App\Entity\CoverObject;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CoverObjectFixtures extends Fixture
{
    public const COVER_OBJECT_REFERENCE = 'cover-object';

    public function load(ObjectManager $manager)
    {
        $src = './public/media/cover/default_cover.jpeg';
        $file = new File(
            $src,
            'default_cover.jpeg',
            'image/jpeg',
            filesize($src)
        );

        $cover = new CoverObject();

        $cover->file = $file;

        $cover->setSlug('cover_tomb_raider');
        $cover->filePath = "cover_tomb_raider.jpeg";

        $manager->persist($cover);
        $manager->flush();

        $this->addReference(self::COVER_OBJECT_REFERENCE, $cover);
    }
}
