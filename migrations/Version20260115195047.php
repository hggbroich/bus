<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260115195047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student CHANGE distance_to_public_school distance_to_public_school DOUBLE PRECISION NOT NULL, CHANGE distance_to_school distance_to_school DOUBLE PRECISION NOT NULL, CHANGE confirmed_distance_to_public_school confirmed_distance_to_public_school DOUBLE PRECISION NOT NULL, CHANGE confirmed_distance_to_school confirmed_distance_to_school DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student CHANGE distance_to_public_school distance_to_public_school INT NOT NULL, CHANGE confirmed_distance_to_public_school confirmed_distance_to_public_school INT NOT NULL, CHANGE distance_to_school distance_to_school INT NOT NULL, CHANGE confirmed_distance_to_school confirmed_distance_to_school INT NOT NULL');
    }
}
