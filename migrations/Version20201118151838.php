<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201118151838 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AD8C11BC9');
        $this->addSql('DROP INDEX IDX_5F9E962AD8C11BC9 ON comments');
        $this->addSql('ALTER TABLE comments DROP related_ticket_id, CHANGE comment_content comment_content VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments ADD related_ticket_id INT NOT NULL, CHANGE comment_content comment_content VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AD8C11BC9 FOREIGN KEY (related_ticket_id) REFERENCES tickets (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5F9E962AD8C11BC9 ON comments (related_ticket_id)');
    }
}
