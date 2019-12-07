<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Page;
use App\Repository\Contract\PageRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class PageRepository extends ServiceEntityRepository implements PageRepositoryInterface
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return Paginator
     */
    public function paginate(int $offset, int $limit): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('p.title', 'ASC');

        return new Paginator($queryBuilder);
    }

    /**
     * @param string $url
     *
     * @return Page|null
     */
    public function findByUrl(string $url): ?Page
    {
        /** @var Page|null $page */
        $page = $this->findOneBy(['url' => $url]);

        return $page;
    }

    /**
     * @param array $criteria
     *
     * @return int
     */
    public function count(array $criteria = []): int
    {
        return parent::count($criteria);
    }
}
