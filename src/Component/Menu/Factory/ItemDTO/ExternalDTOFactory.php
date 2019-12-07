<?php
declare(strict_types=1);

namespace App\Component\Menu\Factory\ItemDTO;

use App\Component\Menu\Form\DTO\ExternalDTO;
use App\Component\Menu\Form\DTO\ItemDTO;
use App\Entity\Menu\Item;

class ExternalDTOFactory implements ConcreteItemDTOFactoryInterface
{
    /**
     * @param int $type
     *
     * @return bool
     */
    public function supports(int $type): bool
    {
        return $type === Item::EXTERNAL;
    }

    /**
     * @param Item $item
     *
     * @return ItemDTO
     */
    public function createFromEntity(Item $item): ItemDTO
    {
        return ExternalDTO::createFromEntity($item);
    }

    /**
     * @return ItemDTO
     */
    public function create(): ItemDTO
    {
        return new ExternalDTO();
    }
}
