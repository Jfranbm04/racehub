<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250308163826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cycling_participant CHANGE time time BIGINT NOT NULL');
        $this->addSql('ALTER TABLE running_participant CHANGE time time BIGINT NOT NULL');
        $this->addSql('ALTER TABLE trail_running_participant CHANGE time time BIGINT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE age age DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE running_participant CHANGE time time DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE cycling_participant CHANGE time time DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE trail_running_participant CHANGE time time DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE `user` CHANGE age age INT NOT NULL');
    }
}
