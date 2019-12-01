<?php
declare(strict_types=1);

namespace App\Doctrine\Filter;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class Publishable extends SQLFilter
{
    /**
     * Gets the SQL query part to add to a query.
     *
     * @param ClassMetaData $targetEntity
     * @param string        $targetTableAlias
     *
     * @return string The constraint SQL if there is available, empty string otherwise.
     *
     * @throws DBALException
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if (!$targetEntity->reflClass->implementsInterface(\App\Doctrine\Contract\Publishable::class)) {
            return '';
        }

        $platform = $this->getConnection()->getDatabasePlatform();

        return sprintf('%s.publishedAt <= %s', $targetTableAlias, $platform->getCurrentTimestampSQL());
    }
}
