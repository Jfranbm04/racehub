<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250307002330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cycling ADD gender VARCHAR(1) NOT NULL');
        $this->addSql('ALTER TABLE running ADD gender VARCHAR(1) NOT NULL, CHANGE entry_fee entry_fee INT DEFAULT NULL');
        $this->addSql('ALTER TABLE running_participant CHANGE running_id running_id INT NOT NULL, CHANGE banned banned TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE trail_running ADD gender VARCHAR(1) NOT NULL');
        $this->addSql('ALTER TABLE trail_running_participant CHANGE banned banned TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user ADD age INT NOT NULL, ADD gender VARCHAR(1) NOT NULL, ADD image LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trail_running_participant CHANGE banned banned TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE cycling DROP gender');
        $this->addSql('ALTER TABLE `user` DROP age, DROP gender, DROP image');
        $this->addSql('ALTER TABLE running DROP gender, CHANGE entry_fee entry_fee INT NOT NULL');
        $this->addSql('ALTER TABLE running_participant CHANGE running_id running_id INT DEFAULT NULL, CHANGE banned banned TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE trail_running DROP gender');
    }
}
