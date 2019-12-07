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
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $name;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="PostTag", mappedBy="tag", cascade={"persist"})
     */
    private $postsTag;

    public function __construct()
    {
        $this->postsTag = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return Collection
     */
    public function getPostsTag(): Collection
    {
        return $this->postsTag;
    }

    /**
     * @param Collection $postTags
     *
     * @return Tag
     */
    public function setPostsTag(Collection $postTags): Tag
    {
        $this->postsTag = $postTags;

        return $this;
    }

    /**
     * @param PostTag $postTag
     *
     * @return Tag
     */
    public function addPostsTag(PostTag $postTag): Tag
    {
        $this->postsTag->add($postTag);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName() ?? '<empty tag>';
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Tag
     */
    public function setName(string $name): Tag
    {
        $this->name = $name;

        return $this;
    }
}
