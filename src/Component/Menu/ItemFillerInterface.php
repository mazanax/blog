<?php
declare(strict_types=1);

namespace App\Component\Menu;

use App\Component\Menu\Form\DTO\ItemDTO;
use App\Entity\Menu\Item;

interface ItemFillerInterface
{
    public function fillFromDTO(Item $item, ItemDTO $dto): void;

    public function fillFromArray(Item $item, array $data): void;
}
