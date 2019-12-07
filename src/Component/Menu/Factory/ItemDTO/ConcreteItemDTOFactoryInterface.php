<?php
declare(strict_types=1);

namespace App\Component\Menu\Factory\ItemDTO;

use App\Component\Menu\Form\DTO\ItemDTO;
use App\Entity\Menu\Item;

interface ConcreteItemDTOFactoryInterface
{
    public function supports(int $type): bool;

    public function createFromEntity(Item $item): ItemDTO;

    public function create(): ItemDTO;
}
