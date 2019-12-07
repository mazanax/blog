<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
use App\Entity\PostTag;
use App\Repository\Contract\PostTagRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

class PostTagRepository extends ServiceEntityRepository implements PostTagRepositoryInterface
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostTag::class);
    }

    /**
     * @param Post   $post
     * @param string $name
     *
     * @return PostTag|null
     */
    public function findByPostAndName(Post $post, string $name): ?PostTag
    {
        /** @var PostTag|null $postTag */
        try {
            $postTag = $this->createQueryBuilder('pt')
                ->join('pt.tag', 't')
                ->where('pt.post = :post')
                ->andWhere('t.name = :name')
                ->setParameter('post', $post)
                ->setParameter('name', $name)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Cannot find post tag: ' . $e->getMessage(), 0, $e);
        }

        return $postTag;
    }

    /**
     * @param Post  $post
     * @param array $keep
     */
    public function removeByPostExcept(Post $post, array $keep): void
    {
        $postTags = $this->findBy(['post' => $post]);

        foreach ($postTags as $postTag) {
            if (in_array($postTag, $keep, true)) {
                continue;
            }

            try {
                $this->getEntityManager()->remove($postTag);
            } catch (ORMException $e) {
                throw new RuntimeException('Cannot find post tag: ' . $e->getMessage(), 0, $e);
            }
        }
    }
}
