<?php
declare(strict_types=1);

namespace App\Component\Menu\Factory\Item;

use App\Component\Menu\Form\DTO\ExternalDTO;
use App\Component\Menu\Form\DTO\ItemDTO;
use App\Entity\Menu\External;
use App\Entity\Menu\Item;

class ExternalItemFactory implements ConcreteItemFactoryInterface
{
    public function supports(int $type): bool
    {
        return $type === Item::EXTERNAL;
    }

    /**
     * @param ExternalDTO|ItemDTO $dto
     *
     * @return Item
     */
    public function createFromDTO(ItemDTO $dto): Item
    {
        return (new External())
            ->setHref($dto->href)
            ->setInNewWindow($dto->inNewWindow)
            ->setTitle($dto->title)
            ->setParent($dto->parent)
            ->setOrder($dto->order);
    }
}
