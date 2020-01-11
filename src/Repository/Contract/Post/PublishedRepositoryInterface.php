<?php
declare(strict_types=1);

namespace App\Repository\Contract\Post;

use App\Entity\Post;
use Doctrine\ORM\QueryBuilder;

interface PublishedRepositoryInterface
{
    public function findById(int $id): Post;

    public function countPublished(): int;

    public function createQueryBuilderForPublishedPosts(int $offset, int $limit): QueryBuilder;

    public function createQueryBuilderForPublishedPostsWithTags(array $tags, int $offset, int $limit): QueryBuilder;
}
