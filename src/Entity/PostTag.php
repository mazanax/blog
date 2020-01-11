<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class PostTag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="tags")
     * @ORM\JoinColumn("post_id", referencedColumnName="id")
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="Tag", inversedBy="postsTag", cascade={"persist"})
     * @ORM\JoinColumn("tag_id", referencedColumnName="id")
     */
    private $tag;

    /**
     * @ORM\Column(type="integer", name="`order`")
     */
    private $order = 0;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(Post $post): PostTag
    {
        $this->post = $post;

        return $this;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function setOrder(int $order): PostTag
    {
        $this->order = $order;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->getTag();
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag($tag): PostTag
    {
        $this->tag = $tag;

        return $this;
    }
}
