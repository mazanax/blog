<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\FileHash;
use App\Repository\Contract\FileHashRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

class FileHashRepository extends ServiceEntityRepository implements FileHashRepositoryInterface
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileHash::class);
    }

    /**
     * @param string $hash
     *
     * @return FileHash|null
     */
    public function findByHash(string $hash): ?FileHash
    {
        /** @var FileHash|null $fileHash */
        $fileHash = $this->findOneBy(['hash' => $hash]);

        return $fileHash;
    }

    /**
     * @param FileHash $fileHash
     */
    public function persistAndFlush(FileHash $fileHash): void
    {
        try {
            $this->getEntityManager()->persist($fileHash);
            $this->getEntityManager()->flush();
        } catch (ORMException $exception) {
            throw new RuntimeException('Error during persist file hash: ' . $exception->getMessage(), 0, $exception);
        }
    }
}
