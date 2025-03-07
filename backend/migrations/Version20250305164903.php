<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250305164903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cycling (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, date DATETIME DEFAULT NULL, distance_km INT NOT NULL, location VARCHAR(255) NOT NULL, coordinates VARCHAR(255) DEFAULT NULL, unevenness INT NOT NULL, entry_fee INT DEFAULT NULL, available_slots INT NOT NULL, status VARCHAR(255) NOT NULL, category VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cycling_participant (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, cycling_id INT DEFAULT NULL, time DATETIME DEFAULT NULL, dorsal INT NOT NULL, banned TINYINT(1) NOT NULL, INDEX IDX_6FD84039A76ED395 (user_id), INDEX IDX_6FD84039A1206764 (cycling_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE running (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, date DATETIME NOT NULL, location VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE running_participant (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, running_id INT NOT NULL, time DATETIME DEFAULT NULL, dorsal INT NOT NULL, banned TINYINT(1) NOT NULL, INDEX IDX_229F0410A76ED395 (user_id), INDEX IDX_229F041083E27A5E (running_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trail_running (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, date DATETIME DEFAULT NULL, distance_km INT NOT NULL, location VARCHAR(255) NOT NULL, coordinates VARCHAR(255) DEFAULT NULL, unevenness INT NOT NULL, entry_fee INT DEFAULT NULL, available_slots INT NOT NULL, status VARCHAR(255) NOT NULL, category VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trail_running_participant (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, trail_running_id INT DEFAULT NULL, time DATETIME DEFAULT NULL, dorsal INT NOT NULL, banned TINYINT(1) NOT NULL, INDEX IDX_4ACEDEF3A76ED395 (user_id), INDEX IDX_4ACEDEF377F47B5C (trail_running_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, banned TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cycling_participant ADD CONSTRAINT FK_6FD84039A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE cycling_participant ADD CONSTRAINT FK_6FD84039A1206764 FOREIGN KEY (cycling_id) REFERENCES cycling (id)');
        $this->addSql('ALTER TABLE running_participant ADD CONSTRAINT FK_229F0410A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE running_participant ADD CONSTRAINT FK_229F041083E27A5E FOREIGN KEY (running_id) REFERENCES running (id)');
        $this->addSql('ALTER TABLE trail_running_participant ADD CONSTRAINT FK_4ACEDEF3A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE trail_running_participant ADD CONSTRAINT FK_4ACEDEF377F47B5C FOREIGN KEY (trail_running_id) REFERENCES trail_running (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cycling_participant DROP FOREIGN KEY FK_6FD84039A76ED395');
        $this->addSql('ALTER TABLE cycling_participant DROP FOREIGN KEY FK_6FD84039A1206764');
        $this->addSql('ALTER TABLE running_participant DROP FOREIGN KEY FK_229F0410A76ED395');
        $this->addSql('ALTER TABLE running_participant DROP FOREIGN KEY FK_229F041083E27A5E');
        $this->addSql('ALTER TABLE trail_running_participant DROP FOREIGN KEY FK_4ACEDEF3A76ED395');
        $this->addSql('ALTER TABLE trail_running_participant DROP FOREIGN KEY FK_4ACEDEF377F47B5C');
        $this->addSql('DROP TABLE cycling');
        $this->addSql('DROP TABLE cycling_participant');
        $this->addSql('DROP TABLE running');
        $this->addSql('DROP TABLE running_participant');
        $this->addSql('DROP TABLE trail_running');
        $this->addSql('DROP TABLE trail_running_participant');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
