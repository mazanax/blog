<?php
declare(strict_types=1);

namespace App\Component\Factory;

use App\Entity\Post;
use App\Entity\PostTag;
use App\Repository\Contract\TagRepositoryInterface;

class PostTagFactory implements PostTagFactoryInterface
{
    private $tagRepository;

    private $tagFactory;

    public function __construct(TagRepositoryInterface $tagRepository, TagFactoryInterface $tagFactory)
    {
        $this->tagRepository = $tagRepository;
        $this->tagFactory = $tagFactory;
    }

    public function create(Post $post, string $tag): PostTag
    {
        $tagEntity = $this->tagRepository->findByName($tag)
                     ?? $this->tagFactory->create($tag);

        $postTag = new PostTag();
        $postTag->setPost($post);
        $postTag->setTag($tagEntity);

        return $postTag;
    }
}
