<?php
declare(strict_types=1);

namespace App\Entity\Menu;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Folder extends Item
{
    /**
     * @return int
     */
    public function getType(): int
    {
        return self::FOLDER;
    }
}
