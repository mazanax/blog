<?php
declare(strict_types=1);

namespace App\Component\PostGetter;

use App\Entity\Post;
use Doctrine\ORM\Tools\Pagination\Paginator;

interface PostGetterStrategyInterface
{
    public function supports(string $strategy): bool;

    public function findById(int $id): ?Post;

    public function findAll(int $offset, int $limit): Paginator;

    public function findByTags(array $tags, int $offset, int $limit): Paginator;
}
