<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241018104846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publications_to_user DROP CONSTRAINT publications_to_user_pkey');
        $this->addSql('ALTER TABLE publications_to_user ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE publications_to_user ADD CONSTRAINT FK_5597A21DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE publications_to_user ADD CONSTRAINT FK_5597A21D38B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5597A21DA76ED395 ON publications_to_user (user_id)');
        $this->addSql('ALTER TABLE publications_to_user ADD PRIMARY KEY (user_id, publication_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE publications_to_user DROP CONSTRAINT FK_5597A21DA76ED395');
        $this->addSql('ALTER TABLE publications_to_user DROP CONSTRAINT FK_5597A21D38B217A7');
        $this->addSql('DROP INDEX IDX_5597A21DA76ED395');
        $this->addSql('DROP INDEX publications_to_user_pkey');
        $this->addSql('ALTER TABLE publications_to_user DROP user_id');
        $this->addSql('ALTER TABLE publications_to_user ADD PRIMARY KEY (publication_id)');
    }
}
