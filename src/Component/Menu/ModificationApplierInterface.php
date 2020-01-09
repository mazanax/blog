<?php
declare(strict_types=1);

namespace App\Component\Menu;

use App\Component\Menu\Form\DTO\SortedMenu;
use App\Component\Menu\Form\DTO\SortedMenuItem;
use App\Entity\Menu\Item;

interface ModificationApplierInterface
{
    public function apply(Item $item, SortedMenuItem $sortedMenuItem): void;

    /**
     * @param Item[]     $items
     * @param SortedMenu $sortedMenu
     */
    public function batchApply(array $items, SortedMenu $sortedMenu): void;
}
