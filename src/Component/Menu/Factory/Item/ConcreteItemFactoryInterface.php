<?php
declare(strict_types=1);

namespace App\Component\Menu\Factory\Item;

use App\Component\Menu\Form\DTO\ItemDTO;
use App\Entity\Menu\Item;

interface ConcreteItemFactoryInterface
{
    public function supports(int $type): bool;

    public function createFromDTO(ItemDTO $dto): Item;
}
