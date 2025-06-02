<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250601190739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__route AS SELECT id, departure_city_id, direction_id, name, description, program, additional_info, duration, route_span FROM route
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE route
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE route (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, departure_city_id INTEGER NOT NULL, direction_id INTEGER NOT NULL, name VARCHAR(128) NOT NULL, description CLOB DEFAULT NULL, program CLOB DEFAULT NULL, additional_info CLOB DEFAULT NULL, duration VARCHAR(128) NOT NULL, route_span VARCHAR(255) DEFAULT NULL --(DC2Type:dateinterval)
            , trip_span VARCHAR(255) NOT NULL --(DC2Type:dateinterval)
            , CONSTRAINT FK_2C42079918B251E FOREIGN KEY (departure_city_id) REFERENCES city (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2C42079AF73D997 FOREIGN KEY (direction_id) REFERENCES city (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO route (id, departure_city_id, direction_id, name, description, program, additional_info, duration, route_span) SELECT id, departure_city_id, direction_id, name, description, program, additional_info, duration, route_span FROM __temp__route
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__route
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2C42079AF73D997 ON route (direction_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2C42079918B251E ON route (departure_city_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trips ADD COLUMN trip_span VARCHAR(255) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__route AS SELECT id, departure_city_id, direction_id, name, description, program, additional_info, duration, route_span FROM route
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE route
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE route (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, departure_city_id INTEGER NOT NULL, direction_id INTEGER NOT NULL, name VARCHAR(128) NOT NULL, description CLOB DEFAULT NULL, program CLOB DEFAULT NULL, additional_info CLOB DEFAULT NULL, duration VARCHAR(128) NOT NULL, route_span VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_2C42079918B251E FOREIGN KEY (departure_city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2C42079AF73D997 FOREIGN KEY (direction_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO route (id, departure_city_id, direction_id, name, description, program, additional_info, duration, route_span) SELECT id, departure_city_id, direction_id, name, description, program, additional_info, duration, route_span FROM __temp__route
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__route
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2C42079918B251E ON route (departure_city_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2C42079AF73D997 ON route (direction_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__trips AS SELECT id, route_id, price, spots_number, start_date FROM trips
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE trips
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE trips (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, route_id INTEGER NOT NULL, price INTEGER NOT NULL, spots_number INTEGER NOT NULL, start_date DATE DEFAULT NULL, CONSTRAINT FK_AA7370DA34ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO trips (id, route_id, price, spots_number, start_date) SELECT id, route_id, price, spots_number, start_date FROM __temp__trips
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__trips
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AA7370DA34ECB4E6 ON trips (route_id)
        SQL);
    }
}
