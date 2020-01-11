<?php
declare(strict_types=1);

namespace App\Entity\Menu;

use App\Component\Menu\Form\DTO\ItemDTO;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Folder extends Item
{
    public function getType(): int
    {
        return self::FOLDER;
    }

    protected function fillChildProperties(ItemDTO $dto): void
    {
    }
}
