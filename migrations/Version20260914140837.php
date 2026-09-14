<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260914140837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE api_access_token (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL, access_token VARCHAR(64) NOT NULL, description VARCHAR(255) NOT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_BCC804C5B6A2DD68 (access_token), UNIQUE INDEX UNIQ_BCC804C5D17F50A6 (uuid), INDEX IDX_BCC804C5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE api_access_token ADD CONSTRAINT FK_BCC804C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_access_token DROP FOREIGN KEY FK_BCC804C5A76ED395');
        $this->addSql('DROP TABLE api_access_token');
    }
}
