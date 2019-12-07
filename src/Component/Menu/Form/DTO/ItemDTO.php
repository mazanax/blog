<?php
declare(strict_types=1);

namespace App\Component\Menu\Form\DTO;

use App\Entity\Menu\Item;
use Symfony\Component\Validator\Constraints as Assert;

abstract class ItemDTO
{
    /**
     * @var int|null
     */
    public $id;
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=64)
     */
    public $title;
    /**
     * @var Item|null
     */
    public $parent;
    /**
     * @var int|null
     */
    public $order;

    /**
     * @param Item $item
     *
     * @return ItemDTO
     */
    public static function createFromEntity(Item $item): ItemDTO
    {
        $dto = new static();
        $dto->id = $item->getId();
        $dto->parent = $item->getParent();
        $dto->title = $item->getTitle();
        $dto->order = $item->getOrder();

        return $dto;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_merge(['type' => static::getType()], get_object_vars($this));
    }

    abstract public function getType(): int;
}
