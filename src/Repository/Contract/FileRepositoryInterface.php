<?php
declare(strict_types=1);

namespace App\Repository\Contract;

use App\Entity\File;

interface FileRepositoryInterface
{
    public function persistAndFlush(File $file): void;
}
