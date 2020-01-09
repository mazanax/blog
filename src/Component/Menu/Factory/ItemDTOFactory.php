<?php
declare(strict_types=1);

namespace App\Component\Menu\Factory;

use App\Component\Menu\Form\DTO\ItemDTO;
use App\Entity\Menu\Item;

class ItemDTOFactory implements ItemDTOFactoryInterface
{
    /**
     * @param Item $parent
     *
     * @return ItemDTO
     */
    public function createWithParent(?Item $parent): ItemDTO
    {
        $dto = new ItemDTO();
        $dto->parent = $parent;

        return $dto;
    }
}
