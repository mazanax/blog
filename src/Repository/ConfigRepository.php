<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Config;
use App\Repository\Contract\ConfigRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ConfigRepository extends ServiceEntityRepository implements ConfigRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Config::class);
    }

    public function all(): array
    {
        return $this->findAll();
    }

    public function findBySlug(string $slug): ?Config
    {
        /** @var Config|null $config */
        $config = $this->findOneBy(['slug' => $slug]);

        return $config;
    }
}
