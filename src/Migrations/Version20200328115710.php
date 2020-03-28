<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200328115710 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE person (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('DROP INDEX IDX_64C19C1727ACA70');
        $this->addSql('CREATE TEMPORARY TABLE __temp__category AS SELECT id, parent_id, name, slug FROM category');
        $this->addSql('DROP TABLE category');
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, slug VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO category (id, parent_id, name, slug) SELECT id, parent_id, name, slug FROM __temp__category');
        $this->addSql('DROP TABLE __temp__category');
        $this->addSql('CREATE INDEX IDX_64C19C1727ACA70 ON category (parent_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__proposal AS SELECT id, title, description, status, progression, progression_max, progression_type, likes FROM proposal');
        $this->addSql('DROP TABLE proposal');
        $this->addSql('CREATE TABLE proposal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, person_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, description BLOB NOT NULL, status INTEGER NOT NULL, progression INTEGER NOT NULL, progression_max INTEGER NOT NULL, progression_type INTEGER NOT NULL, likes INTEGER NOT NULL, CONSTRAINT FK_BFE59472217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO proposal (id, title, description, status, progression, progression_max, progression_type, likes) SELECT id, title, description, status, progression, progression_max, progression_type, likes FROM __temp__proposal');
        $this->addSql('DROP TABLE __temp__proposal');
        $this->addSql('CREATE INDEX IDX_BFE59472217BBB47 ON proposal (person_id)');
        $this->addSql('DROP INDEX IDX_E71725E912469DE2');
        $this->addSql('DROP INDEX IDX_E71725E9F4792058');
        $this->addSql('CREATE TEMPORARY TABLE __temp__proposal_category AS SELECT proposal_id, category_id FROM proposal_category');
        $this->addSql('DROP TABLE proposal_category');
        $this->addSql('CREATE TABLE proposal_category (proposal_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(proposal_id, category_id), CONSTRAINT FK_E71725E9F4792058 FOREIGN KEY (proposal_id) REFERENCES proposal (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E71725E912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO proposal_category (proposal_id, category_id) SELECT proposal_id, category_id FROM __temp__proposal_category');
        $this->addSql('DROP TABLE __temp__proposal_category');
        $this->addSql('CREATE INDEX IDX_E71725E912469DE2 ON proposal_category (category_id)');
        $this->addSql('CREATE INDEX IDX_E71725E9F4792058 ON proposal_category (proposal_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE person');
        $this->addSql('DROP INDEX IDX_64C19C1727ACA70');
        $this->addSql('CREATE TEMPORARY TABLE __temp__category AS SELECT id, parent_id, name, slug FROM category');
        $this->addSql('DROP TABLE category');
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO category (id, parent_id, name, slug) SELECT id, parent_id, name, slug FROM __temp__category');
        $this->addSql('DROP TABLE __temp__category');
        $this->addSql('CREATE INDEX IDX_64C19C1727ACA70 ON category (parent_id)');
        $this->addSql('DROP INDEX IDX_BFE59472217BBB47');
        $this->addSql('CREATE TEMPORARY TABLE __temp__proposal AS SELECT id, title, description, status, progression, progression_max, progression_type, likes FROM proposal');
        $this->addSql('DROP TABLE proposal');
        $this->addSql('CREATE TABLE proposal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description BLOB NOT NULL, status INTEGER NOT NULL, progression INTEGER NOT NULL, progression_max INTEGER NOT NULL, progression_type INTEGER NOT NULL, likes INTEGER NOT NULL)');
        $this->addSql('INSERT INTO proposal (id, title, description, status, progression, progression_max, progression_type, likes) SELECT id, title, description, status, progression, progression_max, progression_type, likes FROM __temp__proposal');
        $this->addSql('DROP TABLE __temp__proposal');
        $this->addSql('DROP INDEX IDX_E71725E9F4792058');
        $this->addSql('DROP INDEX IDX_E71725E912469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__proposal_category AS SELECT proposal_id, category_id FROM proposal_category');
        $this->addSql('DROP TABLE proposal_category');
        $this->addSql('CREATE TABLE proposal_category (proposal_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(proposal_id, category_id))');
        $this->addSql('INSERT INTO proposal_category (proposal_id, category_id) SELECT proposal_id, category_id FROM __temp__proposal_category');
        $this->addSql('DROP TABLE __temp__proposal_category');
        $this->addSql('CREATE INDEX IDX_E71725E9F4792058 ON proposal_category (proposal_id)');
        $this->addSql('CREATE INDEX IDX_E71725E912469DE2 ON proposal_category (category_id)');
    }
}
