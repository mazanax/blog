<?php
declare(strict_types=1);

namespace App\Doctrine\Filter;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use RuntimeException;

class Publishable extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if (!$targetEntity->reflClass->implementsInterface(\App\Doctrine\Contract\Publishable::class)) {
            return '';
        }

        try {
            $platform = $this->getConnection()->getDatabasePlatform();
        } catch (DBALException $e) {
            throw new RuntimeException('Cannot get database platform', 0, $e);
        }

        return sprintf('%s.publishedAt <= %s', $targetTableAlias, $platform->getCurrentTimestampSQL());
    }
}
