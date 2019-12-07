<?php
declare(strict_types=1);

namespace App\Component\Menu\Form\DTO;

use App\Entity\Menu\External;
use App\Entity\Menu\Item;
use Symfony\Component\Validator\Constraints as Assert;

class ExternalDTO extends ItemDTO
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    public $href;

    /**
     * @var bool
     *
     * @Assert\NotNull()
     * @Assert\Type("boolean")
     */
    public $inNewWindow;

    /**
     * @return int
     */
    public function getType(): int
    {
        return Item::EXTERNAL;
    }

    /**
     * @param Item|External $item
     *
     * @return ItemDTO
     */
    public static function createFromEntity(Item $item): ItemDTO
    {
        /** @var static $dto */
        $dto = parent::createFromEntity($item);
        $dto->href = $item->getHref();
        $dto->inNewWindow = $item->isInNewWindow();

        return $dto;
    }
}
