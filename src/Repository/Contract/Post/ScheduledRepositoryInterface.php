<?php
declare(strict_types=1);

namespace App\Repository\Contract\Post;

use App\Entity\Post;
use Doctrine\ORM\QueryBuilder;

interface ScheduledRepositoryInterface
{
    public function findById(int $id): Post;

    public function countScheduled(): int;

    public function createQueryBuilderForScheduledPosts(int $offset, int $limit): QueryBuilder;

    public function createQueryBuilderForScheduledPostsWithTags(array $tags, int $offset, int $limit): QueryBuilder;
}
