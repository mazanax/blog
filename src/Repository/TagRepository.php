<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tag;
use App\Repository\Contract\TagRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

class TagRepository extends ServiceEntityRepository implements TagRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function findByName(string $name): ?Tag
    {
        /** @var Tag|null $tag */
        $tag = $this->findOneBy(['name' => $name]);

        return $tag;
    }

    public function persist(Tag $tag): void
    {
        try {
            $this->getEntityManager()->persist($tag);
        } catch (ORMException $exception) {
            throw new RuntimeException('Error during persist tag: ' . $exception->getMessage(), 0, $exception);
        }
    }

    public function all(): array
    {
        return $this->findAll();
    }
}
