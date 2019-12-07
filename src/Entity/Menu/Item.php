<?php
declare(strict_types=1);

namespace App\Entity\Menu;

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
     * @ORM\OrderBy({"order"="ASC"})
     */
    private $children;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="`order`")
     */
    private $order;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * @return int
     */
    abstract public function getType(): int;

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Item
     */
    public function setTitle(string $title): Item
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return int
     */
    public function getOrder(): ?int
    {
        return $this->order;
    }

    /**
     * @param int $order
     *
     * @return Item
     */
    public function setOrder(?int $order): Item
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return Item
     */
    public function getParent(): ?Item
    {
        return $this->parent;
    }

    /**
     * @param Item $parent
     *
     * @return Item
     */
    public function setParent(?Item $parent): Item
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Item[]|Collection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @param Collection $children
     *
     * @return Item
     */
    public function setChildren(Collection $children): Item
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @param Item $children
     *
     * @return Item
     */
    public function addChild(Item $children): Item
    {
        $this->children->add($children);

        return $this;
    }
}
