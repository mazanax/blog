<?php
declare(strict_types=1);

namespace App\Component\Factory;

use App\Entity\File;

interface FileFactoryInterface
{
    public function create(string $name, string $path, string $publicPath): File;
}
