<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200328115232 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_64C19C1727ACA70 ON category (parent_id)');
        $this->addSql('CREATE TABLE proposal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description BLOB NOT NULL, status INTEGER NOT NULL, progression INTEGER NOT NULL, progression_max INTEGER NOT NULL, progression_type INTEGER NOT NULL, likes INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE proposal_category (proposal_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(proposal_id, category_id))');
        $this->addSql('CREATE INDEX IDX_E71725E9F4792058 ON proposal_category (proposal_id)');
        $this->addSql('CREATE INDEX IDX_E71725E912469DE2 ON proposal_category (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE proposal');
        $this->addSql('DROP TABLE proposal_category');
    }
}
