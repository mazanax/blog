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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $href;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $inNewWindow;

    /**
     * @return string
     */
    public function getHref(): ?string
    {
        return $this->href;
    }

    /**
     * @return bool
     */
    public function isInNewWindow(): bool
    {
        return $this->inNewWindow ?? false;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return self::EXTERNAL;
    }

    /**
     * @param ItemDTO $dto
     */
    protected function fillChildProperties(ItemDTO $dto): void
    {
        $this->href = $dto->href;
        $this->inNewWindow = $dto->inNewWindow;
    }
}
