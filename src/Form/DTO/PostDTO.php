<?php
declare(strict_types=1);

namespace App\Form\DTO;

use App\Entity\Post;
use App\Entity\PostTag;
use App\Validation\Constraint\UniqueEntityDTO;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use RuntimeException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntityDTO(entityClass="App\Entity\Post", field="url")
 */
final class PostDTO
{
    /**
     * @var int|null
     */
    public $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=255)
     * @Assert\Regex(pattern="/^[\-_a-zA-Z0-9]+$/")
     */
    public $url;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    public $title;

    /**
     * @var string
     */
    public $preview;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public $text;

    /**
     * @var DateTimeInterface
     */
    public $publishedAt;

    /**
     * @var array
     *
     * @Assert\All(
     *     @Assert\Type(type="string"),
     *     @Assert\Length(min=2, max=64)
     * )
     */
    public $tags;

    public function __construct()
    {
        try {
            $this->publishedAt = new DateTimeImmutable('now');
        } catch (Exception $exception) {
            throw new RuntimeException('Cannot create datetime: ' . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * @param Post $post
     *
     * @return PostDTO
     */
    public static function createFromEntity(Post $post): self
    {
        $dto = new static();
        $dto->id = $post->getId();
        $dto->url = $post->getUrl();
        $dto->title = $post->getTitle();
        $dto->preview = $post->getPreview();
        $dto->text = $post->getText();
        try {
            $dto->publishedAt = $post->getPublishedAt() ?: new DateTimeImmutable('now');
        } catch (Exception $e) {
            $dto->publishedAt = null;
        }
        $dto->tags = $post->getTags()
            ->map(static function (PostTag $tag) {
                return (string) $tag->getTag();
            })
            ->toArray();

        return $dto;
    }
}
