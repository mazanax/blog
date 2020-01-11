<?php
declare(strict_types=1);

namespace App\Entity\Menu;

use App\Component\Menu\Form\DTO\ItemDTO;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Page extends Item
{
    /**
     * @ORM\OneToOne(targetEntity="\App\Entity\Page")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    private $page;

    public function getPage(): ?\App\Entity\Page
    {
        return $this->page;
    }

    public function getType(): int
    {
        return self::PAGE;
    }

    protected function fillChildProperties(ItemDTO $dto): void
    {
        $this->page = $dto->page;
    }
}
