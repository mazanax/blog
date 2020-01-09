<?php
declare(strict_types=1);

namespace App\Component\Menu;

use App\Entity\Menu\Item;

class Sorter implements SorterInterface
{
    /**
     * @param Item[] $items
     */
    public function sort(array &$items): void
    {
        $groupedByParent = [];

        foreach ($items as $item) {
            $parent = $item->getParent() ? $item->getParent()->getId() : 'root';
            $groupedByParent[$parent][] = $item;
        }

        foreach ($groupedByParent as &$groupItems) {
            usort($groupItems, static function (Item $first, Item $second) {
                return $first->getSortableRank() < $second->getSortableRank() ? -1 : 1;
            });
        }
        unset($groupItems);

        foreach ($items as $item) {
            if (null !== $item->getSortableRank()) {
                continue;
            }

            $parent = $item->getParent() ? $item->getParent()->getId() : 'root';

            /** @var Item $lastElementInGroup */
            $lastElementInGroup = end($groupedByParent[$parent]) ?? $item->getParent();
            $item->setSortableRank($lastElementInGroup->getSortableRank() + 1);

            foreach ($items as $nextItem) {
                if ($nextItem === $item || $nextItem->getSortableRank() < $item->getSortableRank()) {
                    continue;
                }

                $nextItem->setSortableRank($nextItem->getSortableRank() + 1);
            }
        }
    }
}
