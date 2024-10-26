<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241024074806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication ADD created_at DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE publication ADD updated_at DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE publication DROP date');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE publication ADD date VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE publication DROP created_at');
        $this->addSql('ALTER TABLE publication DROP updated_at');
    }
}
