<?php
declare(strict_types=1);

namespace App\Component\Menu;

use App\Component\Menu\Form\DTO\SortedMenu;
use App\Component\Menu\Form\DTO\SortedMenuItem;
use App\Entity\Menu\Item;

class ModificationApplier implements ModificationApplierInterface
{
    public function apply(Item $item, SortedMenuItem $sortedMenuItem): void
    {
        $item->setParent($sortedMenuItem->parent);
        $item->setSortableRank($sortedMenuItem->sortableRank);
    }

    /**
     * @param Item[]     $items
     * @param SortedMenu $sortedMenu
     */
    public function batchApply(array $items, SortedMenu $sortedMenu): void
    {
        foreach ($items as $item) {
            $id = $item->getId();

            if (!isset($sortedMenu[$id])) {
                continue;
            }

            $this->apply($item, $sortedMenu[$id]);
        }
    }
}