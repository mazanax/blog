<?php
declare(strict_types=1);

namespace App\Entity\Menu;

use App\Component\Menu\Form\DTO\ItemDTO;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Tag extends Item
{
    /**
     * @ORM\OneToOne(targetEntity="\App\Entity\Tag")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     */
    private $tag;

    public function getTag(): ?\App\Entity\Tag
    {
        return $this->tag;
    }

    public function getType(): int
    {
        return self::TAG;
    }

    protected function fillChildProperties(ItemDTO $dto): void
    {
        $this->tag = $dto->tag;
    }
}
