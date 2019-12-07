<?php
declare(strict_types=1);

namespace App\Repository\Contract;

use App\Entity\Tag;

interface TagRepositoryInterface
{
    public function findByName(string $name): ?Tag;

    public function persist(Tag $tag): void;

    public function all(): array;
}
