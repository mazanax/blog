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
     * @var string
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="tags")
     * @ORM\JoinColumn("post_id", referencedColumnName="id")
     */
    private $post;

    /**
     * @var Tag
     *
     * @ORM\ManyToOne(targetEntity="Tag", inversedBy="postsTag", cascade={"persist"})
     * @ORM\JoinColumn("tag_id", referencedColumnName="id")
     */
    private $tag;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="`order`")
     */
    private $order = 0;

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return Post
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     *
     * @return PostTag
     */
    public function setPost(Post $post): PostTag
    {
        $this->post = $post;

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
     * @return PostTag
     */
    public function setOrder(int $order): PostTag
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->getTag();
    }

    /**
     * @return mixed
     */
    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    /**
     * @param mixed $tag
     *
     * @return PostTag
     */
    public function setTag($tag): PostTag
    {
        $this->tag = $tag;

        return $this;
    }
}
