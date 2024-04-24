<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240424080853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add post categories.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD filed_in_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D88B245B8 FOREIGN KEY (filed_in_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D88B245B8 ON post (filed_in_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D88B245B8');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP INDEX IDX_5A8A6C8D88B245B8 ON post');
        $this->addSql('ALTER TABLE post DROP filed_in_id');
    }
}
