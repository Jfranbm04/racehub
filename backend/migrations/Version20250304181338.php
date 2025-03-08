<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250304181338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE running_participant (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, running_id INT DEFAULT NULL, time DATETIME DEFAULT NULL, dorsal INT NOT NULL, banned TINYINT(1) DEFAULT NULL, INDEX IDX_229F0410A76ED395 (user_id), INDEX IDX_229F041083E27A5E (running_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trail_running_participant (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, trail_running_id INT DEFAULT NULL, time DATETIME DEFAULT NULL, dorsal INT NOT NULL, banned TINYINT(1) DEFAULT NULL, INDEX IDX_4ACEDEF3A76ED395 (user_id), INDEX IDX_4ACEDEF377F47B5C (trail_running_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE running_participant ADD CONSTRAINT FK_229F0410A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE running_participant ADD CONSTRAINT FK_229F041083E27A5E FOREIGN KEY (running_id) REFERENCES running (id)');
        $this->addSql('ALTER TABLE trail_running_participant ADD CONSTRAINT FK_4ACEDEF3A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE trail_running_participant ADD CONSTRAINT FK_4ACEDEF377F47B5C FOREIGN KEY (trail_running_id) REFERENCES trail_running (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE running_participant DROP FOREIGN KEY FK_229F0410A76ED395');
        $this->addSql('ALTER TABLE running_participant DROP FOREIGN KEY FK_229F041083E27A5E');
        $this->addSql('ALTER TABLE trail_running_participant DROP FOREIGN KEY FK_4ACEDEF3A76ED395');
        $this->addSql('ALTER TABLE trail_running_participant DROP FOREIGN KEY FK_4ACEDEF377F47B5C');
        $this->addSql('DROP TABLE running_participant');
        $this->addSql('DROP TABLE trail_running_participant');
    }
}
