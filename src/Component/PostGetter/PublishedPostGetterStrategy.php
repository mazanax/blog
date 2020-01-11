<?php
declare(strict_types=1);

namespace App\Component\PostGetter;

use App\Constant\PostStrategy;
use App\Entity\Post;
use App\Repository\Contract\PostRepositoryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PublishedPostGetterStrategy implements PostGetterStrategyInterface
{
    private $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function supports(string $strategy): bool
    {
        return $strategy === PostStrategy::PUBLISHED;
    }

    public function findById(int $id): ?Post
    {
        return $this->postRepository->findById($id);
    }

    public function findAll(int $offset, int $limit): Paginator
    {
        return new Paginator($this->postRepository->createQueryBuilderForPublishedPosts($offset, $limit));
    }

    public function findByTags(array $tags, int $offset, int $limit): Paginator
    {
        $queryBuilder = $this->postRepository->createQueryBuilderForPublishedPostsWithTags($tags, $offset, $limit);

        return new Paginator($queryBuilder);
    }
}
