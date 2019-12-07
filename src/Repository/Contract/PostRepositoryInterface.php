<?php
declare(strict_types=1);

namespace App\Repository\Contract;

use App\Entity\Post;
use Doctrine\ORM\QueryBuilder;

interface PostRepositoryInterface
{
    public function countPublished(): int;

    public function countScheduled(): int;

    public function findById(int $id): Post;

    public function createQueryBuilderForPublishedPosts(int $offset, int $limit): QueryBuilder;

    public function createQueryBuilderForScheduledPosts(int $offset, int $limit): QueryBuilder;

    public function createQueryBuilderForPublishedPostsWithTags(array $tags, int $offset, int $limit): QueryBuilder;

    public function createQueryBuilderForScheduledPostsWithTags(array $tags, int $offset, int $limit): QueryBuilder;
}
