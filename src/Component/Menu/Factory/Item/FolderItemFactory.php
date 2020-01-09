<?php
declare(strict_types=1);

namespace App\Component\Menu\Factory\Item;

use App\Entity\Menu\Folder;
use App\Entity\Menu\Item;

class FolderItemFactory implements ConcreteItemFactoryInterface
{
    public function supports(int $type): bool
    {
        return $type === Item::FOLDER;
    }

    public function create(): Item
    {
        return new Folder();
    }
}
