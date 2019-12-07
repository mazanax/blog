<?php
declare(strict_types=1);

namespace App\Entity\Menu;

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
     * @param string $href
     *
     * @return External
     */
    public function setHref(string $href): External
    {
        $this->href = $href;

        return $this;
    }

    /**
     * @return bool
     */
    public function isInNewWindow(): bool
    {
        return $this->inNewWindow ?? false;
    }

    /**
     * @param bool $inNewWindow
     *
     * @return External
     */
    public function setInNewWindow(bool $inNewWindow): External
    {
        $this->inNewWindow = $inNewWindow;

        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return self::EXTERNAL;
    }
}
