<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\File;
use App\Repository\Contract\FileRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

class FileRepository extends ServiceEntityRepository implements FileRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, File::class);
    }

    public function persistAndFlush(File $file): void
    {
        try {
            $this->getEntityManager()->persist($file);
            $this->getEntityManager()->flush();
        } catch (ORMException $exception) {
            throw new RuntimeException('Error during persist file: ' . $exception->getMessage(), 0, $exception);
        }
    }
}
