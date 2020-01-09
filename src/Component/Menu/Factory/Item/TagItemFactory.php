<?php
declare(strict_types=1);

namespace App\Component\Menu\Factory\Item;

use App\Entity\Menu\Item;
use App\Entity\Menu\Tag;

class TagItemFactory implements ConcreteItemFactoryInterface
{
    public function supports(int $type): bool
    {
        return $type === Item::TAG;
    }

    public function create(): Item
    {
        return new Tag();
    }
}
