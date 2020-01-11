<?php
declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use RuntimeException;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $preview;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $publishedAt;

    /**
     * @ORM\OneToMany(targetEntity="PostTag", mappedBy="post", cascade={"persist"})
     * @ORM\OrderBy({"order"="ASC"})
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): Post
    {
        $this->url = $url;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): Post
    {
        $this->title = $title;

        return $this;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(string $preview): Post
    {
        $this->preview = $preview;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): Post
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function isPublished(): bool
    {
        try {
            return $this->getPublishedAt() <= new DateTimeImmutable();
        } catch (Exception $exception) {
            throw new RuntimeException($exception->getMessage(), 0, $exception);
        }
    }

    public function getPublishedAt(): ?DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(DateTimeInterface $publishedAt): Post
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function setTags(Collection $tags): Post
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist(): void
    {
        try {
            $this->createdAt = new DateTimeImmutable();
        } catch (Exception $e) {
            throw new RuntimeException('Cannot create datetime instance', 0, $e);
        }
    }
}
