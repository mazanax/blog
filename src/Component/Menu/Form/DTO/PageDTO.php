<?php
declare(strict_types=1);

namespace App\Component\Menu\Form\DTO;

use App\Entity\Menu\Item;
use App\Entity\Menu\Page;
use Symfony\Component\Validator\Constraints as Assert;

class PageDTO extends ItemDTO
{
    /**
     * @var \App\Entity\Page
     *
     * @Assert\NotBlank()
     */
    public $page;

    /**
     * @return int
     */
    public function getType(): int
    {
        return Item::PAGE;
    }

    /**
     * @param Item|Page $item
     *
     * @return ItemDTO
     */
    public static function createFromEntity(Item $item): ItemDTO
    {
        /** @var static $dto */
        $dto = parent::createFromEntity($item);
        $dto->page = $item->getPage();

        return $dto;
    }
}
