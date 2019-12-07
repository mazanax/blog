<?php
declare(strict_types=1);

namespace App\Entity\Menu;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Page extends Item
{
    /**
     * @var \App\Entity\Page
     *
     * @ORM\OneToOne(targetEntity="\App\Entity\Page")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    private $page;

    /**
     * @return \App\Entity\Page
     */
    public function getPage(): ?\App\Entity\Page
    {
        return $this->page;
    }

    /**
     * @param \App\Entity\Page $page
     *
     * @return Page
     */
    public function setPage(\App\Entity\Page $page): Page
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return self::PAGE;
    }
}
