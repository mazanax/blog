<?php
declare(strict_types=1);

namespace App\Component\Menu\Form\DTO;

use App\Entity\Menu\Item;
use App\Entity\Menu\Tag;
use Symfony\Component\Validator\Constraints as Assert;

class TagDTO extends ItemDTO
{
    /**
     * @var \App\Entity\Tag
     *
     * @Assert\NotBlank()
     */
    public $tag;

    /**
     * @return int
     */
    public function getType(): int
    {
        return Item::TAG;
    }

    /**
     * @param Item|Tag $item
     *
     * @return ItemDTO
     */
    public static function createFromEntity(Item $item): ItemDTO
    {
        /** @var static $dto */
        $dto = parent::createFromEntity($item);
        $dto->tag = $item->getTag();

        return $dto;
    }
}
