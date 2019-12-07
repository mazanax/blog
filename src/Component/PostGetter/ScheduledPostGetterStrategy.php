<?php
declare(strict_types=1);

namespace App\Component\PostGetter;

use App\Constant\PostStrategy;
use App\Entity\Post;
use App\Repository\Contract\PostRepositoryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ScheduledPostGetterStrategy implements PostGetterStrategyInterface
{
    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;

    /**
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @param string $strategy
     *
     * @return bool
     */
    public function supports(string $strategy): bool
    {
        return $strategy === PostStrategy::SCHEDULED;
    }

    /**
     * @param int $id
     *
     * @return Post|null
     */
    public function findById(int $id): ?Post
    {
        return $this->postRepository->findById($id);
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return Paginator
     */
    public function findAll(int $offset, int $limit): Paginator
    {
        return new Paginator($this->postRepository->createQueryBuilderForScheduledPosts($offset, $limit));
    }

    /**
     * @param array $tags
     * @param int   $offset
     * @param int   $limit
     *
     * @return Paginator
     */
    public function findByTags(array $tags, int $offset, int $limit): Paginator
    {
        $queryBuilder = $this->postRepository->createQueryBuilderForScheduledPostsWithTags($tags, $offset, $limit);

        return new Paginator($queryBuilder);
    }
}
