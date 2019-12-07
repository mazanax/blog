<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200102174652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create item table';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE item (id UUID NOT NULL, parent_id UUID DEFAULT NULL, page_id INT DEFAULT NULL, title VARCHAR(64) NOT NULL, level INT NOT NULL, "order" INT NOT NULL, type INT NOT NULL, href VARCHAR(255) DEFAULT NULL, in_new_window BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1F1B251E727ACA70 ON item (parent_id)');
        $this->addSql('CREATE INDEX IDX_1F1B251EC4663E4 ON item (page_id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E727ACA70 FOREIGN KEY (parent_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EC4663E4 FOREIGN KEY (page_id) REFERENCES page (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA IF NOT EXISTS public');
        $this->addSql('ALTER TABLE item DROP CONSTRAINT FK_1F1B251E727ACA70');
        $this->addSql('DROP TABLE item');
    }
}
