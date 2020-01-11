<?php
declare(strict_types=1);

namespace App\Repository\Contract\Post;

use App\Entity\Post;
use Doctrine\ORM\QueryBuilder;

interface DraftRepositoryInterface
{
    public function findById(int $id): Post;

    public function countDrafts(): int;

    public function createQueryBuilderForDraftPosts(int $offset, int $limit): QueryBuilder;

    public function createQueryBuilderForDraftPostsWithTags(array $tags, int $offset, int $limit): QueryBuilder;
}
