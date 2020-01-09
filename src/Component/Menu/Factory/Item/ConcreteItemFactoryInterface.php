<?php
declare(strict_types=1);

namespace App\Component\Menu\Factory\Item;

use App\Entity\Menu\Item;

interface ConcreteItemFactoryInterface
{
    public function supports(int $type): bool;

    public function create(): Item;
}
