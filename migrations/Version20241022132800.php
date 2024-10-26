<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241022132800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE space_ship ADD created_at DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE space_ship ADD updated_at DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE space_ship DROP color');
        $this->addSql('ALTER TABLE space_ship DROP size');
        $this->addSql('ALTER TABLE space_ship DROP crew_capacity');
        $this->addSql('ALTER TABLE space_ship DROP max_speed');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE space_ship ADD color VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE space_ship ADD size VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE space_ship ADD crew_capacity SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE space_ship ADD max_speed SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE space_ship DROP created_at');
        $this->addSql('ALTER TABLE space_ship DROP updated_at');
    }
}
