<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201118154133 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, user_comments_id INT NOT NULL, tickets_id INT DEFAULT NULL, comment_content VARCHAR(255) NOT NULL, status LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_5F9E962ACA2C5C13 (user_comments_id), INDEX IDX_5F9E962A8FDC0E9A (tickets_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962ACA2C5C13 FOREIGN KEY (user_comments_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A8FDC0E9A FOREIGN KEY (tickets_id) REFERENCES tickets (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE comments');
    }
}
