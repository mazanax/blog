<?php
declare(strict_types=1);

namespace App\Entity\Menu;

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
     * @param \App\Entity\Tag $tag
     *
     * @return Tag
     */
    public function setTag(\App\Entity\Tag $tag): Tag
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return self::TAG;
    }
}
