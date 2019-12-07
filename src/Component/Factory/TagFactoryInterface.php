<?php
declare(strict_types=1);

namespace App\Component\Factory;

use App\Entity\Tag;

interface TagFactoryInterface
{
    public function create(string $name): Tag;
}
