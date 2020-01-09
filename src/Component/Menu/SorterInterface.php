<?php
declare(strict_types=1);

namespace App\Component\Menu;

interface SorterInterface
{
    public function sort(array &$items): void;
}
