<?php
declare(strict_types=1);

namespace App\Component\Menu\Form\DTO;

use App\Entity\Menu\Item;

class OrderedMenuDTO
{
    /**
     * @var Item[]
     */
    public $items = [];
}
