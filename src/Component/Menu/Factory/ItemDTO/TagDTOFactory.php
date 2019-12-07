<?php
declare(strict_types=1);

namespace App\Component\Menu\Factory\ItemDTO;

use App\Component\Menu\Form\DTO\ItemDTO;
use App\Component\Menu\Form\DTO\TagDTO;
use App\Entity\Menu\Item;

class TagDTOFactory implements ConcreteItemDTOFactoryInterface
{
    /**
     * @param int $type
     *
     * @return bool
     */
    public function supports(int $type): bool
    {
        return $type === Item::TAG;
    }

    /**
     * @param Item $item
     *
     * @return ItemDTO
     */
    public function createFromEntity(Item $item): ItemDTO
    {
        return TagDTO::createFromEntity($item);
    }

    /**
     * @return ItemDTO
     */
    public function create(): ItemDTO
    {
        return new TagDTO();
    }
}
