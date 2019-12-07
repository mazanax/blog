<?php
declare(strict_types=1);

namespace App\Component\Menu\Factory\Item;

use App\Component\Menu\Form\DTO\ItemDTO;
use App\Component\Menu\Form\DTO\PageDTO;
use App\Entity\Menu\Item;
use App\Entity\Menu\Page;

class PageItemFactory implements ConcreteItemFactoryInterface
{
    /**
     * @param int $type
     *
     * @return bool
     */
    public function supports(int $type): bool
    {
        return $type === Item::PAGE;
    }

    /**
     * @param PageDTO|ItemDTO $dto
     *
     * @return Item
     */
    public function createFromDTO(ItemDTO $dto): Item
    {
        return (new Page())
            ->setPage($dto->page)
            ->setTitle($dto->title)
            ->setParent($dto->parent)
            ->setOrder($dto->order);
    }
}
