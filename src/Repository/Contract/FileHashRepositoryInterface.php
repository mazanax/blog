<?php
declare(strict_types=1);

namespace App\Repository\Contract;

use App\Entity\FileHash;

interface FileHashRepositoryInterface
{
    public function findByHash(string $hash): ?FileHash;

    public function persistAndFlush(FileHash $fileHash): void;
}
