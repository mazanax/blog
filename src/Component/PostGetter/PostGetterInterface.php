<?php
declare(strict_types=1);

namespace App\Component\PostGetter;

use App\Entity\Post;
use Doctrine\ORM\Tools\Pagination\Paginator;

interface PostGetterInterface
{
    public function findById(string $strategy, int $id): ?Post;

    public function findAll(string $strategy, int $offset, int $limit): Paginator;

    public function findByTags(string $strategy, array $tags, int $offset, int $limit): Paginator;
}
