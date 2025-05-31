<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250531174749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE route_photo ADD COLUMN photo VARCHAR(256) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__route_photo AS SELECT id, route_id FROM route_photo
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE route_photo
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE route_photo (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, route_id INTEGER DEFAULT NULL, CONSTRAINT FK_89C8B6B634ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO route_photo (id, route_id) SELECT id, route_id FROM __temp__route_photo
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__route_photo
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_89C8B6B634ECB4E6 ON route_photo (route_id)
        SQL);
    }
}
