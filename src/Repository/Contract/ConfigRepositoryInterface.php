<?php
declare(strict_types=1);

namespace App\Repository\Contract;

use App\Entity\Config;

interface ConfigRepositoryInterface
{
    public function all(): array;

    public function findBySlug(string $slug): ?Config;
}
