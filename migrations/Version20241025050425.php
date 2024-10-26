<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241025050425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication ALTER created_at SET DEFAULT NULL');
        $this->addSql('ALTER TABLE publication ALTER updated_at SET DEFAULT NULL');
        $this->addSql('ALTER TABLE space_ship ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE space_ship ALTER created_at SET DEFAULT NULL');
        $this->addSql('ALTER TABLE space_ship ALTER updated_at SET DEFAULT NULL');
        $this->addSql('ALTER TABLE space_ship ADD CONSTRAINT FK_914691AEA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_914691AEA76ED395 ON space_ship (user_id)');
        $this->addSql('ALTER TABLE "user" ALTER created_at SET DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ALTER updated_at SET DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE space_ship DROP CONSTRAINT FK_914691AEA76ED395');
        $this->addSql('DROP INDEX IDX_914691AEA76ED395');
        $this->addSql('ALTER TABLE space_ship DROP user_id');
        $this->addSql('ALTER TABLE space_ship ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE space_ship ALTER updated_at DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER updated_at DROP NOT NULL');
        $this->addSql('ALTER TABLE publication ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE publication ALTER updated_at DROP NOT NULL');
    }
}