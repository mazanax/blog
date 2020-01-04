<?php
declare(strict_types=1);

namespace App\Component\Filler;

use App\Component\Factory\PostTagFactoryInterface;
use App\Entity\Post;
use App\Entity\PostTag;
use App\Form\DTO\PostDTO;
use App\Repository\Contract\PostTagRepositoryInterface;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use RuntimeException;

class PostFiller implements PostFillerInterface
{
    /**
     * @var PostTagRepositoryInterface
     */
    private $postTagRepository;

    /**
     * @var PostTagFactoryInterface
     */
    private $postTagFactory;

    /**
     * @param PostTagRepositoryInterface $postTagRepository
     * @param PostTagFactoryInterface    $postTagFactory
     */
    public function __construct(PostTagRepositoryInterface $postTagRepository, PostTagFactoryInterface $postTagFactory)
    {
        $this->postTagRepository = $postTagRepository;
        $this->postTagFactory = $postTagFactory;
    }

    /**
     * @param Post    $post
     * @param PostDTO $dto
     */
    public function fillFromDto(Post $post, PostDTO $dto): void
    {
        $post->setUrl($dto->url);
        $post->setTitle($dto->title);
        $post->setPreview($dto->preview ?? '');
        $post->setText($dto->text);
        try {
            $post->setPublishedAt($dto->publishedAt ?? new DateTimeImmutable());
        } catch (Exception $e) {
            throw new RuntimeException('[PostFiller] Cannot fill entity from DTO: ' . $e->getMessage(), 0, $e);
        }
        $tags = array_map(
            function (string $tag, int $order) use ($post) {
                $postTag = $this->getPostTag($post, $tag);
                $postTag->setOrder($order);

                return $postTag;
            },
            $dto->tags,
            array_keys($dto->tags)
        );

        $post->setTags(new ArrayCollection($tags));
    }

    /**
     * @param Post   $post
     * @param string $tag
     *
     * @return PostTag
     */
    private function getPostTag(Post $post, string $tag): PostTag
    {
        if (!$post->getId()) {
            return $this->postTagFactory->create($post, $tag);
        }

        return $this->postTagRepository->findByPostAndName($post, $tag)
               ?? $this->postTagFactory->create($post, $tag);
    }
}
