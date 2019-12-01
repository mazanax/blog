<?php

namespace App\DataFixtures;

use App\Entity\Post;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 23; $i++) {
            $post = new Post();
            $post->setTitle(bin2hex(random_bytes(32)));
            $post->setPreview(bin2hex(random_bytes(1024)));
            $post->setText(bin2hex(random_bytes(4096)));
            $post->setPublishedAt(DateTimeImmutable::createFromFormat('U', (string) (time() - $i * 86400)));
            $post->setUrl('my-post-' . bin2hex(random_bytes(10)));

            $manager->persist($post);
        }

        $manager->flush();
    }
}
