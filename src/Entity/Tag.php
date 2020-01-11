<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="PostTag", mappedBy="tag", cascade={"persist"})
     */
    private $postsTag;

    public function __construct()
    {
        $this->postsTag = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPostsTag(): Collection
    {
        return $this->postsTag;
    }

    public function setPostsTag(Collection $postTags): Tag
    {
        $this->postsTag = $postTags;

        return $this;
    }

    public function addPostsTag(PostTag $postTag): Tag
    {
        $this->postsTag->add($postTag);

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName() ?? '<empty tag>';
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): Tag
    {
        $this->name = $name;

        return $this;
    }
}
