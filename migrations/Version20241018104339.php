<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241018104339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE publications_to_user (publication_id INT NOT NULL, PRIMARY KEY(publication_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5597A21D38B217A7 ON publications_to_user (publication_id)');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d64938b217a7');
        $this->addSql('DROP INDEX idx_8d93d64938b217a7');
        $this->addSql('ALTER TABLE "user" DROP publication_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE publications_to_user');
        $this->addSql('ALTER TABLE "user" ADD publication_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d64938b217a7 FOREIGN KEY (publication_id) REFERENCES publication (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_8d93d64938b217a7 ON "user" (publication_id)');
    }
}
