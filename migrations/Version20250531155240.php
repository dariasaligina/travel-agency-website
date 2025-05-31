<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250531155240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE city (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(128) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE route (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, departure_city_id INTEGER NOT NULL, direction_id INTEGER NOT NULL, name VARCHAR(128) NOT NULL, description CLOB DEFAULT NULL, program CLOB DEFAULT NULL, additional_info CLOB DEFAULT NULL, duration VARCHAR(128) NOT NULL, CONSTRAINT FK_2C42079918B251E FOREIGN KEY (departure_city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2C42079AF73D997 FOREIGN KEY (direction_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2C42079918B251E ON route (departure_city_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2C42079AF73D997 ON route (direction_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE route_photo (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, route_id INTEGER DEFAULT NULL, CONSTRAINT FK_89C8B6B634ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_89C8B6B634ECB4E6 ON route_photo (route_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE triprequest (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, trip_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, people_number INTEGER NOT NULL, active BOOLEAN NOT NULL, processed BOOLEAN NOT NULL, CONSTRAINT FK_2DAB33B6A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trips (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2DAB33B6A5BC2E0E ON triprequest (trip_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE trips (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, route_id INTEGER NOT NULL, trip_span VARCHAR(255) NOT NULL --(DC2Type:dateinterval)
            , price INTEGER NOT NULL, spots_number INTEGER NOT NULL, CONSTRAINT FK_AA7370DA34ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AA7370DA34ECB4E6 ON trips (route_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE city
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE route
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE route_photo
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE triprequest
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE trips
        SQL);
    }
}
