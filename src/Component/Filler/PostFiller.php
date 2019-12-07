<?php
declare(strict_types=1);

namespace App\Component\Filler;

use App\Component\Factory\PostTagFactoryInterface;
use App\Entity\Post;
use App\Form\DTO\PostDTO;
use App\Repository\Contract\PostTagRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;

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
        $post->setPreview($dto->preview);
        $post->setText($dto->text);
        $post->setPublishedAt($dto->publishedAt);

        $tags = array_map(
            function (string $tag, int $key) use ($post) {
                $postTag = $this->postTagRepository->findByPostAndName($post, $tag)
                           ?? $this->postTagFactory->create($post, $tag, $key);

                $postTag->setOrder($key);

                return $postTag;
            },
            $dto->tags,
            array_keys($dto->tags)
        );

        $post->setTags(new ArrayCollection($tags));
    }
}
