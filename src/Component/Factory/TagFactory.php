<?php
declare(strict_types=1);

namespace App\Component\Factory;

use App\Entity\Tag;

class TagFactory implements TagFactoryInterface
{
    public function create(string $name): Tag
    {
        $tag = new Tag();
        $tag->setName($name);

        return $tag;
    }
}
