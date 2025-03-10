<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306004532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE running DROP description, DROP distance_km, DROP coordinates, DROP entry_fee, DROP available_slots, DROP status, DROP category, DROP image');
        $this->addSql('ALTER TABLE running_participant CHANGE running_id running_id INT NOT NULL, CHANGE banned banned TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE trail_running_participant CHANGE banned banned TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trail_running_participant CHANGE banned banned TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE running ADD description VARCHAR(255) DEFAULT NULL, ADD distance_km INT NOT NULL, ADD coordinates VARCHAR(255) DEFAULT NULL, ADD entry_fee INT NOT NULL, ADD available_slots INT NOT NULL, ADD status VARCHAR(255) NOT NULL, ADD category VARCHAR(255) DEFAULT NULL, ADD image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE running_participant CHANGE running_id running_id INT DEFAULT NULL, CHANGE banned banned TINYINT(1) DEFAULT NULL');
    }
}
