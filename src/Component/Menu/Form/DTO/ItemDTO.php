<?php
declare(strict_types=1);

namespace App\Component\Menu\Form\DTO;

use App\Entity\Menu\Item;
use Symfony\Component\Validator\Constraints as Assert;

final class ItemDTO
{
    public $id;

    /**
     * @Assert\NotNull()
     * @Assert\Choice(callback="allowedChoices")
     * @Assert\NotEqualTo(1, groups={"Child"})
     */
    public $type;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=64)
     */
    public $title;

    /**
     * @Assert\Valid()
     */
    public $parent;

    /**
     * @Assert\Type(type="numeric")
     * @Assert\PositiveOrZero()
     */
    public $sortableRank;

    /**
     * @Assert\NotBlank(groups={"Link"})
     * @Assert\Length(max=255)
     */
    public $href;

    /**
     * @Assert\NotNull(groups={"Link"})
     * @Assert\Type("boolean")
     */
    public $inNewWindow;

    /**
     * @Assert\NotBlank(groups={"Page"})
     * @Assert\Valid()
     */
    public $page;

    /**
     * @Assert\NotBlank(groups={"Tag"})
     * @Assert\Valid()
     */
    public $tag;

    public static function allowedChoices(): array
    {
        return array_flip(Item::TYPES);
    }
}
