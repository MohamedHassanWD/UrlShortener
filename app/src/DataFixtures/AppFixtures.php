<?php

namespace App\DataFixtures;

use App\Entity\ShortUrl;
use App\Services\Shortener;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $shortener = new Shortener();

        $shorty = new ShortUrl();
        $shorty->setUrl('http://php.net');
        $shorty->setUser(1);
        $shorty->setAlias($shortener->generateShortCode(12300));
        $shorty->setCreatedAt(new \DateTime('now'));
        $manager->persist($shorty);
        $manager->flush();

        $shorty = new ShortUrl();
        $shorty->setUrl('http://google.com');
        $shorty->setUser(2);
        $shorty->setAlias($shortener->generateShortCode(13300));
        $shorty->setCreatedAt(new \DateTime('now'));
        $manager->persist($shorty);
        $manager->flush();

        $shorty = new ShortUrl();
        $shorty->setUrl('http://php.net');
        $shorty->setUser(3);
        $shorty->setAlias($shortener->generateShortCode(14300));
        $shorty->setCreatedAt(new \DateTime('now'));
        $manager->persist($shorty);
        $manager->flush();
    }
}
