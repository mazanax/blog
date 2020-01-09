<?php
declare(strict_types=1);

namespace App\Component\Menu\Form\DTO;

use ArrayAccess;
use InvalidArgumentException;

class SortedMenu implements ArrayAccess
{
    private $items = [];

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    public function offsetGet($offset): SortedMenuItem
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        if (!$value instanceof SortedMenuItem) {
            throw new InvalidArgumentException('Expected instanceof ' . SortedMenuItem::class);
        }

        $this->items[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    public function items(): array
    {
        return array_values($this->items);
    }

    public function setItems(array $items): SortedMenu
    {
        $this->items = array_reduce(
            $items,
            static function (array $acc, SortedMenuItem $item) {
                $acc[$item->id] = $item;

                return $acc;
            },
            []
        );

        return $this;
    }
}
