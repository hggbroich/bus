<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260628155144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE city (id INT UNSIGNED AUTO_INCREMENT NOT NULL, plz VARCHAR(5) NOT NULL, name VARCHAR(255) NOT NULL, federal_state VARCHAR(255) NOT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_2D5B0234D17F50A6 (uuid), UNIQUE INDEX UNIQ_2D5B0234D2280DCB5E237E06 (plz, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE street (id INT UNSIGNED AUTO_INCREMENT NOT NULL, city_id INT UNSIGNED DEFAULT NULL, name VARCHAR(255) NOT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_F0EED3D8D17F50A6 (uuid), INDEX IDX_F0EED3D88BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE street ADD CONSTRAINT FK_F0EED3D88BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE city');
        $this->addSql('ALTER TABLE street DROP FOREIGN KEY FK_F0EED3D88BAC62AF');
        $this->addSql('DROP TABLE street');
    }
}
