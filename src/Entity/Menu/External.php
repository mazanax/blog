<?php
declare(strict_types=1);

namespace App\Entity\Menu;

use App\Component\Menu\Form\DTO\ItemDTO;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class External extends Item
{
    /**
     * @ORM\Column(type="string")
     */
    private $href;

    /**
     * @ORM\Column(type="boolean")
     */
    private $inNewWindow;

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function isInNewWindow(): bool
    {
        return $this->inNewWindow ?? false;
    }

    public function getType(): int
    {
        return self::EXTERNAL;
    }

    protected function fillChildProperties(ItemDTO $dto): void
    {
        $this->href = $dto->href;
        $this->inNewWindow = $dto->inNewWindow;
    }
}
