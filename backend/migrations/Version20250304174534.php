<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250304174534 extends AbstractMigration
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
        $this->addSql('ALTER TABLE cycling_participant ADD CONSTRAINT FK_6FD84039A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE cycling_participant ADD CONSTRAINT FK_6FD84039A1206764 FOREIGN KEY (cycling_id) REFERENCES cycling (id)');
        $this->addSql('ALTER TABLE user ADD name VARCHAR(255) NOT NULL, ADD banned TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cycling_participant DROP FOREIGN KEY FK_6FD84039A76ED395');
        $this->addSql('ALTER TABLE cycling_participant DROP FOREIGN KEY FK_6FD84039A1206764');
        $this->addSql('DROP TABLE cycling');
        $this->addSql('DROP TABLE cycling_participant');
        $this->addSql('ALTER TABLE `user` DROP name, DROP banned');
    }
}
