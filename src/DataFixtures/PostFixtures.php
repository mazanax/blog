<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\PostTag;
use App\Entity\Tag;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;

class PostFixtures extends Fixture
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $tags = [];

        foreach (['Test', 'Post', 'Fixture'] as $tagName) {
            $tag = new Tag();
            $tag->setName($tagName);

            $manager->persist($tag);

            $tags[] = $tag;
        }

        for ($i = 0; $i < 23; $i++) {
            $post = new Post();
            $post->setTitle(bin2hex(random_bytes(32)));
            $post->setPreview(bin2hex(random_bytes(1024)));
            $post->setText(bin2hex(random_bytes(4096)));
            $post->setPublishedAt(DateTimeImmutable::createFromFormat('U', (string) (time() - $i * 86400)));
            $post->setUrl('my-post-' . bin2hex(random_bytes(10)));

            if ($i % 7 === 0) {
                $postTags = [];

                foreach ($tags as $idx => $tag) {
                    $postTag = new PostTag();
                    $postTag->setOrder($idx + 1);
                    $postTag->setTag($tag);

                    $manager->persist($postTag);
                    $postTags[] = $postTag;
                }

                $post->setTags(new ArrayCollection($postTags));
            }

            $manager->persist($post);
        }

        $manager->flush();
    }
}
