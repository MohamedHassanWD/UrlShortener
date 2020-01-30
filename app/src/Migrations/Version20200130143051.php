<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200130143051 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE short_url DROP FOREIGN KEY FK_83360531A76ED395');
        $this->addSql('DROP INDEX IDX_83360531A76ED395 ON short_url');
        $this->addSql('ALTER TABLE short_url CHANGE user user INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE short_url CHANGE user user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE short_url ADD CONSTRAINT FK_83360531A76ED395 FOREIGN KEY (user) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_83360531A76ED395 ON short_url (user)');
    }
}
