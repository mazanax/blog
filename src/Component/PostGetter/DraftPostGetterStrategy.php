<?php
declare(strict_types=1);

namespace App\Component\PostGetter;

use App\Constant\PostStrategy;
use App\Entity\Post;
use App\Repository\Contract\Post\DraftRepositoryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DraftPostGetterStrategy implements PostGetterStrategyInterface
{
    private $postRepository;

    public function __construct(DraftRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function supports(string $strategy): bool
    {
        return PostStrategy::DRAFTS === $strategy;
    }

    public function findById(int $id): ?Post
    {
        return $this->postRepository->findById($id);
    }

    public function findAll(int $offset, int $limit): Paginator
    {
        return new Paginator($this->postRepository->createQueryBuilderForDraftPosts($offset, $limit));
    }

    public function findByTags(array $tags, int $offset, int $limit): Paginator
    {
        return new Paginator($this->postRepository->createQueryBuilderForDraftPostsWithTags($tags, $offset, $limit));
    }
}
