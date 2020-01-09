<?php
declare(strict_types=1);

namespace App\Component\Menu\Factory;

use App\Component\Menu\Form\DTO\ItemDTO;
use App\Entity\Menu\Item;

interface ItemDTOFactoryInterface
{
    public function createWithParent(?Item $parent): ItemDTO;
}
