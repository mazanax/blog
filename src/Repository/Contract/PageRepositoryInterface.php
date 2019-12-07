<?php
declare(strict_types=1);

namespace App\Repository\Contract;

use App\Entity\Page;
use Doctrine\ORM\Tools\Pagination\Paginator;

interface PageRepositoryInterface
{
    public function count(array $criteria = []): int;

    public function paginate(int $offset, int $limit): Paginator;

    public function findByUrl(string $url): ?Page;
}
