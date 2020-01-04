<?php
declare(strict_types=1);

namespace App\Component\Factory;

use App\Entity\Post;
use App\Entity\PostTag;
use App\Repository\Contract\TagRepositoryInterface;

class PostTagFactory implements PostTagFactoryInterface
{
    /**
     * @var TagRepositoryInterface
     */
    private $tagRepository;
    /**
     * @var TagFactoryInterface
     */
    private $tagFactory;

    /**
     * @param TagRepositoryInterface $tagRepository
     * @param TagFactoryInterface    $tagFactory
     */
    public function __construct(TagRepositoryInterface $tagRepository, TagFactoryInterface $tagFactory)
    {
        $this->tagRepository = $tagRepository;
        $this->tagFactory = $tagFactory;
    }

    /**
     * @param Post   $post
     * @param string $tag
     * @param int    $order
     *
     * @return PostTag
     */
    public function create(Post $post, string $tag, int $order): PostTag
    {
        $tagEntity = $this->tagRepository->findByName($tag)
                     ?? $this->tagFactory->create($tag);

        $postTag = new PostTag();
        $postTag->setPost($post);
        $postTag->setTag($tagEntity);
        $postTag->setOrder($order);

        return $postTag;
    }
}