<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201118133730 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, user_comments_id INT NOT NULL, related_ticket_id INT NOT NULL, tickets_id INT DEFAULT NULL, comment_content VARCHAR(255) DEFAULT NULL, INDEX IDX_5F9E962ACA2C5C13 (user_comments_id), INDEX IDX_5F9E962AD8C11BC9 (related_ticket_id), INDEX IDX_5F9E962A8FDC0E9A (tickets_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tickets (id INT AUTO_INCREMENT NOT NULL, assigned_to_id INT DEFAULT NULL, created_by_id INT NOT NULL, ticket_status VARCHAR(255) NOT NULL, ticket_priority INT NOT NULL, content VARCHAR(255) NOT NULL, INDEX IDX_54469DF4F4BD7827 (assigned_to_id), INDEX IDX_54469DF4B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962ACA2C5C13 FOREIGN KEY (user_comments_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AD8C11BC9 FOREIGN KEY (related_ticket_id) REFERENCES tickets (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A8FDC0E9A FOREIGN KEY (tickets_id) REFERENCES tickets (id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4F4BD7827 FOREIGN KEY (assigned_to_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AD8C11BC9');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A8FDC0E9A');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE tickets');
    }
}
