<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
use App\Repository\Contract\PostRepositoryInterface;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use RuntimeException;

class PostRepository extends ServiceEntityRepository implements PostRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function createQueryBuilderForPublishedPosts(int $offset, int $limit): QueryBuilder
    {
        try {
            return $this->createQueryBuilder('p')
                ->where('p.publishedAt <= :now')
                ->setParameter('now', new DateTimeImmutable())
                ->orderBy('p.publishedAt', 'DESC')
                ->setMaxResults($limit)
                ->setFirstResult($offset);
        } catch (Exception $e) {
            throw new RuntimeException('Cannot get published posts: ' . $e->getMessage(), 0, $e);
        }
    }

    public function createQueryBuilderForScheduledPosts(int $offset, int $limit): QueryBuilder
    {
        try {
            return $this->createQueryBuilder('p')
                ->where('p.publishedAt > :now')
                ->setParameter('now', new DateTimeImmutable())
                ->orderBy('p.publishedAt', 'DESC')
                ->setMaxResults($limit)
                ->setFirstResult($offset);
        } catch (Exception $e) {
            throw new RuntimeException('Cannot get scheduled posts: ' . $e->getMessage(), 0, $e);
        }
    }

    public function createQueryBuilderForPublishedPostsWithTags(array $tags, int $offset, int $limit): QueryBuilder
    {
        try {
            return $this->createQueryBuilderForPublishedPosts($offset, $limit)
                ->join('p.tags', 'pt')
                ->join('pt.tag', 't')
                ->andWhere('t.name IN (:tags)')
                ->setParameter('tags', $tags);
        } catch (Exception $e) {
            throw new RuntimeException('Cannot get published posts with tags: ' . $e->getMessage(), 0, $e);
        }
    }

    public function createQueryBuilderForScheduledPostsWithTags(array $tags, int $offset, int $limit): QueryBuilder
    {
        try {
            return $this->createQueryBuilderForScheduledPosts($offset, $limit)
                ->join('p.tags', 'pt')
                ->join('pt.tag', 't')
                ->andWhere('t.name IN (:tags)')
                ->setParameter('tags', $tags);
        } catch (Exception $e) {
            throw new RuntimeException('Cannot get published posts with tags: ' . $e->getMessage(), 0, $e);
        }
    }

    public function findById(int $id): Post
    {
        /** @var Post $post */
        $post = $this->find($id);

        return $post;
    }

    public function countPublished(): int
    {
        try {
            return (int) $this->createQueryBuilder('p')
                ->where('p.publishedAt <= :now')
                ->setParameter('now', new DateTimeImmutable())
                ->select('COUNT(p) as count')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (Exception $e) {
            throw new RuntimeException('Cannot count published posts: ' . $e->getMessage(), 0, $e);
        }
    }

    public function countScheduled(): int
    {
        try {
            return (int) $this->createQueryBuilder('p')
                ->where('p.publishedAt > :now')
                ->setParameter('now', new DateTimeImmutable())
                ->select('COUNT(p) as count')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (Exception $e) {
            throw new RuntimeException('Cannot count scheduled posts: ' . $e->getMessage(), 0, $e);
        }
    }
}
