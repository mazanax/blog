<?php
declare(strict_types=1);

namespace App\Component\Menu\Repository;

use App\Component\Menu\Repository\Contract\ItemRepositoryInterface;
use App\Entity\Menu\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

class ItemRepository extends ServiceEntityRepository implements ItemRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function getTopItems(): array
    {
        return $this->findBy(['parent' => null], ['sortableRank' => 'ASC']);
    }

    public function all(): array
    {
        return $this->findBy([], ['sortableRank' => 'ASC']);
    }

    public function persist(Item $item): void
    {
        try {
            $this->getEntityManager()->persist($item);
        } catch (ORMException $exception) {
            throw new RuntimeException('Error during persist menu item: ' . $exception->getMessage(), 0, $exception);
        }
    }

    public function batchPersist(array $items): void
    {
        foreach ($items as $item) {
            $this->persist($item);
        }
    }

    public function flush(): void
    {
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException | ORMException $e) {
            throw new RuntimeException('Error during flush menu items: ' . $e->getMessage(), 0, $e);
        }
    }
}
