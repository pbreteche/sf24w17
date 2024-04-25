<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240425095332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add post to user authoredBy relation.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO user (email, roles) SELECT \'anonymous@example.com\', \'[]\' WHERE NOT EXISTS (SELECT * FROM user)');
        $this->addSql('ALTER TABLE post ADD authored_by_id INT DEFAULT NULL');
        $this->addSql('UPDATE post SET authored_by_id = 1 WHERE authored_by_id IS NULL');
        $this->addSql('ALTER TABLE post CHANGE authored_by_id authored_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D22BF0A45 FOREIGN KEY (authored_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D22BF0A45 ON post (authored_by_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D22BF0A45');
        $this->addSql('DROP INDEX IDX_5A8A6C8D22BF0A45 ON post');
        $this->addSql('ALTER TABLE post DROP authored_by_id');
    }
}
