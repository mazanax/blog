<?php
declare(strict_types=1);

namespace App\Component\Menu\Factory\Item;

use App\Entity\Menu\External;
use App\Entity\Menu\Item;

class ExternalItemFactory implements ConcreteItemFactoryInterface
{
    public function supports(int $type): bool
    {
        return $type === Item::EXTERNAL;
    }

    public function create(): Item
    {
        return new External();
    }
}
