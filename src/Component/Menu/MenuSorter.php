<?php
declare(strict_types=1);

namespace App\Component\Menu;

use App\Entity\Menu\Item;

class MenuSorter implements MenuSorterInterface
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
                return $first->getOrder() < $second->getOrder() ? -1 : 1;
            });
        }
        unset($groupItems);

        foreach ($items as $item) {
            if (null !== $item->getOrder()) {
                continue;
            }

            $parent = $item->getParent() ? $item->getParent()->getId() : 'root';

            /** @var Item $lastElementInGroup */
            $lastElementInGroup = end($groupedByParent[$parent]) ?? $item->getParent();
            $item->setOrder($lastElementInGroup->getOrder() + 1);

            foreach ($items as $nextItem) {
                if ($nextItem === $item || $nextItem->getOrder() < $item->getOrder()) {
                    continue;
                }

                $nextItem->setOrder($nextItem->getOrder() + 1);
            }
        }
    }
}
