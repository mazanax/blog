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
     * @var \App\Entity\Tag
     *
     * @ORM\OneToOne(targetEntity="\App\Entity\Tag")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     */
    private $tag;

    /**
     * @return \App\Entity\Tag|null
     */
    public function getTag(): ?\App\Entity\Tag
    {
        return $this->tag;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return self::TAG;
    }

    /**
     * @param ItemDTO $dto
     */
    protected function fillChildProperties(ItemDTO $dto): void
    {
        $this->tag = $dto->tag;
    }
}
