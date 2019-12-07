<?php
declare(strict_types=1);

namespace App\Component\Menu\Form\DTO;

use App\Entity\Menu\Folder;
use App\Entity\Menu\Item;

class FolderDTO extends ItemDTO
{
    /**
     * @return int
     */
    public function getType(): int
    {
        return Item::FOLDER;
    }

    /**
     * @param Item|Folder $item
     *
     * @return ItemDTO
     */
    public static function createFromEntity(Item $item): ItemDTO
    {
        /** @var static $dto */
        $dto = parent::createFromEntity($item);

        return $dto;
    }
}
