<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200102105807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates file hash table and changes reference of file table';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE file_hash (id UUID NOT NULL, hash VARCHAR(32) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE file_hash ADD CONSTRAINT FK_58440AD6BF396750 FOREIGN KEY (id) REFERENCES file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE file ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE file ADD public_path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE file DROP hash');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA IF NOT EXISTS public');
        $this->addSql('DROP TABLE file_hash');
        $this->addSql('ALTER TABLE file ADD hash VARCHAR(32) NOT NULL');
        $this->addSql('ALTER TABLE file DROP name');
        $this->addSql('ALTER TABLE file DROP public_path');
    }
}
