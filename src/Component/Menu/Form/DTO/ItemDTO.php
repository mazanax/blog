<?php
declare(strict_types=1);

namespace App\Component\Menu\Form\DTO;

use App\Entity\Menu\Item;
use App\Entity\Page;
use App\Entity\Tag;
use Symfony\Component\Validator\Constraints as Assert;

final class ItemDTO
{
    /**
     * @var int|null
     */
    public $id;

    /**
     * @var int
     *
     * @Assert\NotNull()
     * @Assert\Choice(callback="allowedChoices")
     * @Assert\NotEqualTo(1, groups={"Child"})
     */
    public $type;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=64)
     */
    public $title;

    /**
     * @var Item|null
     *
     * @Assert\Valid()
     */
    public $parent;

    /**
     * @var int|null
     *
     * @Assert\Type(type="numeric")
     * @Assert\PositiveOrZero()
     */
    public $sortableRank;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"Link"})
     * @Assert\Length(max=255)
     */
    public $href;

    /**
     * @var bool
     *
     * @Assert\NotNull(groups={"Link"})
     * @Assert\Type("boolean")
     */
    public $inNewWindow;

    /**
     * @var Page
     *
     * @Assert\NotBlank(groups={"Page"})
     * @Assert\Valid()
     */
    public $page;

    /**
     * @var Tag
     *
     * @Assert\NotBlank(groups={"Tag"})
     * @Assert\Valid()
     */
    public $tag;

    /**
     * @return array
     */
    public static function allowedChoices(): array
    {
        return array_flip(Item::TYPES);
    }
}
