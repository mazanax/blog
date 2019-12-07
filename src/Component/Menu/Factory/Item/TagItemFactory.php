<?php
declare(strict_types=1);

namespace App\Component\Menu\Factory\Item;

use App\Component\Menu\Form\DTO\ItemDTO;
use App\Component\Menu\Form\DTO\TagDTO;
use App\Entity\Menu\Item;
use App\Entity\Menu\Tag;

class TagItemFactory implements ConcreteItemFactoryInterface
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
     * @param TagDTO|ItemDTO $dto
     *
     * @return Item
     */
    public function createFromDTO(ItemDTO $dto): Item
    {
        return (new Tag())
            ->setTag($dto->tag)
            ->setTitle($dto->title)
            ->setParent($dto->parent)
            ->setOrder($dto->order);
    }
}
