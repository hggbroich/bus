<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251110153835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE country (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, iso_code VARCHAR(2) NOT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_5373C96662B6A45E (iso_code), UNIQUE INDEX UNIQ_5373C966D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fare_level (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, level VARCHAR(255) NOT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_E280FD252D5B0234 (city), UNIQUE INDEX UNIQ_E280FD25D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE id_entity (entity_id VARCHAR(255) NOT NULL, id VARCHAR(255) NOT NULL, expiry DATETIME NOT NULL, PRIMARY KEY(entity_id, id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, channel VARCHAR(255) NOT NULL, level INT NOT NULL, message LONGTEXT NOT NULL, time DATETIME NOT NULL, details JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_processed_messages (id INT AUTO_INCREMENT NOT NULL, run_id INT NOT NULL, attempt SMALLINT NOT NULL, message_type VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, dispatched_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', received_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', finished_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', wait_time BIGINT NOT NULL, handle_time BIGINT NOT NULL, memory_usage INT NOT NULL, transport VARCHAR(255) NOT NULL, tags VARCHAR(255) DEFAULT NULL, failure_type VARCHAR(255) DEFAULT NULL, failure_message LONGTEXT DEFAULT NULL, results JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT UNSIGNED AUTO_INCREMENT NOT NULL, student_id INT UNSIGNED DEFAULT NULL, payment_interval_id INT UNSIGNED DEFAULT NULL, stop_id INT UNSIGNED DEFAULT NULL, public_school_id INT UNSIGNED DEFAULT NULL, depositor_country_id INT UNSIGNED NOT NULL, ticket_id INT UNSIGNED DEFAULT NULL, fare_level_id INT UNSIGNED NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birthday DATE NOT NULL, gender VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, house_number VARCHAR(255) NOT NULL, plz INT DEFAULT NULL, city VARCHAR(255) NOT NULL, bus_company_customer_id VARCHAR(255) DEFAULT NULL, sgb12 TINYINT(1) NOT NULL, confirmed_distance_to_public_school INT NOT NULL, confirmed_distance_to_school INT NOT NULL, iban VARCHAR(255) NOT NULL, encrypted_iban LONGTEXT NOT NULL, depositor_firstname VARCHAR(255) NOT NULL, depositor_lastname VARCHAR(255) NOT NULL, depositor_birthday DATE NOT NULL, depositor_street VARCHAR(255) NOT NULL, depositor_house_number VARCHAR(255) NOT NULL, depositor_plz INT DEFAULT NULL, depositor_city VARCHAR(255) NOT NULL, depositor_phone_number VARCHAR(255) NOT NULL, depositor_email VARCHAR(255) NOT NULL, confirmations JSON NOT NULL COMMENT \'(DC2Type:json)\', is_incorrect TINYINT(1) NOT NULL, last_checked_at DATETIME DEFAULT NULL, uuid VARCHAR(36) NOT NULL, created_at DATETIME NOT NULL, created_by VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_F5299398D17F50A6 (uuid), INDEX IDX_F5299398CB944F1A (student_id), INDEX IDX_F5299398A6D6E4F4 (payment_interval_id), INDEX IDX_F52993983902063D (stop_id), INDEX IDX_F5299398EE0D9C6F (public_school_id), INDEX IDX_F529939866056101 (depositor_country_id), INDEX IDX_F5299398700047D2 (ticket_id), INDEX IDX_F5299398146D5D09 (fare_level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_interval (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_5900ADA3D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE school (id INT UNSIGNED AUTO_INCREMENT NOT NULL, school_number INT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, plz INT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_F99EDABBD17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting (id INT UNSIGNED AUTO_INCREMENT NOT NULL, `key` VARCHAR(255) NOT NULL, `data` JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', UNIQUE INDEX UNIQ_9F74B8984E645A7E (`key`), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stop (id INT UNSIGNED AUTO_INCREMENT NOT NULL, external_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_B95616B69F75D7B0 (external_id), UNIQUE INDEX UNIQ_B95616B65E237E06 (name), UNIQUE INDEX UNIQ_B95616B6D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT UNSIGNED AUTO_INCREMENT NOT NULL, payment_interval_id INT UNSIGNED DEFAULT NULL, stop_id INT UNSIGNED DEFAULT NULL, public_school_id INT UNSIGNED DEFAULT NULL, external_id VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, grade VARCHAR(255) NOT NULL, birthday DATE NOT NULL, gender VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, house_number VARCHAR(255) NOT NULL, plz INT DEFAULT NULL, city VARCHAR(255) NOT NULL, entrance_date DATE NOT NULL, leave_date DATE DEFAULT NULL, bus_company_customer_id VARCHAR(255) DEFAULT NULL, sgb12 TINYINT(1) NOT NULL, distance_to_public_school INT NOT NULL, confirmed_distance_to_public_school INT NOT NULL, distance_to_school INT NOT NULL, confirmed_distance_to_school INT NOT NULL, uuid VARCHAR(36) NOT NULL, created_at DATETIME NOT NULL, created_by VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_B723AF339F75D7B0 (external_id), UNIQUE INDEX UNIQ_B723AF33E7927C74 (email), UNIQUE INDEX UNIQ_B723AF33D17F50A6 (uuid), INDEX IDX_B723AF33A6D6E4F4 (payment_interval_id), INDEX IDX_B723AF333902063D (stop_id), INDEX IDX_B723AF33EE0D9C6F (public_school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_sibling (id INT UNSIGNED AUTO_INCREMENT NOT NULL, order_id INT UNSIGNED NOT NULL, student_at_school_id INT UNSIGNED DEFAULT NULL, school_id INT UNSIGNED DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, birthday DATE DEFAULT NULL, INDEX IDX_7F8DD0498D9F6D38 (order_id), INDEX IDX_7F8DD049723BF84B (student_at_school_id), INDEX IDX_7F8DD049C32A47EE (school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, priority INT NOT NULL, default_external_id VARCHAR(255) NOT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_97A0ADA362A6DC27 (priority), UNIQUE INDEX UNIQ_97A0ADA34B86C406 (default_external_id), UNIQUE INDEX UNIQ_97A0ADA3D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket_payment_interval (id INT UNSIGNED AUTO_INCREMENT NOT NULL, ticket_id INT UNSIGNED NOT NULL, payment_interval_id INT UNSIGNED NOT NULL, external_id VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_33B2193A9F75D7B0 (external_id), INDEX IDX_33B2193A700047D2 (ticket_id), INDEX IDX_33B2193AA6D6E4F4 (payment_interval_id), UNIQUE INDEX UNIQ_33B2193A700047D2A6D6E4F4 (ticket_id, payment_interval_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT UNSIGNED AUTO_INCREMENT NOT NULL, idp_identifier BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', username VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_8D93D64966D2FA6C (idp_identifier), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_student (user_id INT UNSIGNED NOT NULL, student_id INT UNSIGNED NOT NULL, INDEX IDX_EF2EB139A76ED395 (user_id), INDEX IDX_EF2EB139CB944F1A (student_id), PRIMARY KEY(user_id, student_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sessions (sess_id VARBINARY(128) NOT NULL, sess_data LONGBLOB NOT NULL, sess_lifetime INT UNSIGNED NOT NULL, sess_time INT UNSIGNED NOT NULL, INDEX sess_lifetime_idx (sess_lifetime), PRIMARY KEY(sess_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_bin` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A6D6E4F4 FOREIGN KEY (payment_interval_id) REFERENCES payment_interval (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993983902063D FOREIGN KEY (stop_id) REFERENCES stop (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398EE0D9C6F FOREIGN KEY (public_school_id) REFERENCES school (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939866056101 FOREIGN KEY (depositor_country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398146D5D09 FOREIGN KEY (fare_level_id) REFERENCES fare_level (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33A6D6E4F4 FOREIGN KEY (payment_interval_id) REFERENCES payment_interval (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF333902063D FOREIGN KEY (stop_id) REFERENCES stop (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33EE0D9C6F FOREIGN KEY (public_school_id) REFERENCES school (id)');
        $this->addSql('ALTER TABLE student_sibling ADD CONSTRAINT FK_7F8DD0498D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_sibling ADD CONSTRAINT FK_7F8DD049723BF84B FOREIGN KEY (student_at_school_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE student_sibling ADD CONSTRAINT FK_7F8DD049C32A47EE FOREIGN KEY (school_id) REFERENCES school (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE ticket_payment_interval ADD CONSTRAINT FK_33B2193A700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ticket_payment_interval ADD CONSTRAINT FK_33B2193AA6D6E4F4 FOREIGN KEY (payment_interval_id) REFERENCES payment_interval (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_student ADD CONSTRAINT FK_EF2EB139A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_student ADD CONSTRAINT FK_EF2EB139CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398CB944F1A');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A6D6E4F4');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993983902063D');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398EE0D9C6F');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939866056101');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398700047D2');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398146D5D09');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33A6D6E4F4');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF333902063D');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33EE0D9C6F');
        $this->addSql('ALTER TABLE student_sibling DROP FOREIGN KEY FK_7F8DD0498D9F6D38');
        $this->addSql('ALTER TABLE student_sibling DROP FOREIGN KEY FK_7F8DD049723BF84B');
        $this->addSql('ALTER TABLE student_sibling DROP FOREIGN KEY FK_7F8DD049C32A47EE');
        $this->addSql('ALTER TABLE ticket_payment_interval DROP FOREIGN KEY FK_33B2193A700047D2');
        $this->addSql('ALTER TABLE ticket_payment_interval DROP FOREIGN KEY FK_33B2193AA6D6E4F4');
        $this->addSql('ALTER TABLE user_student DROP FOREIGN KEY FK_EF2EB139A76ED395');
        $this->addSql('ALTER TABLE user_student DROP FOREIGN KEY FK_EF2EB139CB944F1A');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE fare_level');
        $this->addSql('DROP TABLE id_entity');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE messenger_processed_messages');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE payment_interval');
        $this->addSql('DROP TABLE school');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE stop');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE student_sibling');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE ticket_payment_interval');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_student');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP TABLE sessions');
    }
}
