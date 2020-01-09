<?php
declare(strict_types=1);

namespace App\Entity\Menu;

use App\Component\Menu\Form\DTO\ItemDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="integer")
 * @ORM\DiscriminatorMap({-1 = "External", 1 = "Folder", 2 = "Page", 3 = "Tag"})
 */
abstract class Item
{
    public const EXTERNAL = -1;
    public const FOLDER = 1;
    public const PAGE = 2;
    public const TAG = 3;

    public const TYPES = [
        self::EXTERNAL => 'External Link',
        self::FOLDER => 'Folder',
        self::PAGE => 'Static Page',
        self::TAG => 'Tag',
    ];

    public const NAMES = [
        self::EXTERNAL => 'External',
        self::FOLDER => 'Folder',
        self::PAGE => 'Page',
        self::TAG => 'Tag',
    ];

    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $title;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @var Item[]
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="parent", cascade={"persist", "remove"})
     * @ORM\OrderBy({"sortableRank"="ASC"})
     */
    private $children;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="`order`")
     */
    private $sortableRank;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    abstract public function getType(): int;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getSortableRank(): ?int
    {
        return $this->sortableRank;
    }

    public function setSortableRank(?int $sortableRank): Item
    {
        $this->sortableRank = $sortableRank;

        return $this;
    }

    public function setParent(?Item $parent): Item
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent(): ?Item
    {
        return $this->parent;
    }

    /**
     * @return Item[]|Collection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function fillFromDTO(ItemDTO $dto): void
    {
        $this->title = $dto->title;
        $this->parent = $dto->parent;
        $this->sortableRank = $dto->sortableRank;

        $this->fillChildProperties($dto);
    }

    abstract protected function fillChildProperties(ItemDTO $dto): void;
}
