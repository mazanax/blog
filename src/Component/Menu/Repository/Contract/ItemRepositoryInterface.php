<?php
declare(strict_types=1);

namespace App\Component\Menu\Repository\Contract;

use App\Entity\Menu\Item;

interface ItemRepositoryInterface
{
    /**
     * @return Item[]
     */
    public function getTopItems(): array;

    /**
     * @return Item[]
     */
    public function all(): array;

    public function persist(Item $item): void;

    public function flush(): void;
}
