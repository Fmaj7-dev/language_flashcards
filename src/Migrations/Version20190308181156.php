<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190308181156 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_language CHANGE user_id user_id INT DEFAULT NULL, CHANGE language_id language_id SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE vocabulary_category CHANGE vocabulary_id vocabulary_id INT DEFAULT NULL, CHANGE category_id category_id SMALLINT DEFAULT NULL');
        //$this->addSql('ALTER TABLE user DROP FOREIGN KEY user_ibfk_1');
        //$this->addSql('DROP INDEX native_language ON user');
        $this->addSql('ALTER TABLE user ADD roles text NOT NULL, ADD password VARCHAR(255) NOT NULL, CHANGE native_language native_language INT NOT NULL, CHANGE name name VARCHAR(180) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6495E237E06 ON user (name)');
        $this->addSql('ALTER TABLE guess CHANGE user_id user_id INT DEFAULT NULL, CHANGE vocabulary_id vocabulary_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vocabulary CHANGE language_a language_a SMALLINT DEFAULT NULL, CHANGE language_b language_b SMALLINT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE guess CHANGE user_id user_id INT NOT NULL, CHANGE vocabulary_id vocabulary_id INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_8D93D6495E237E06 ON user');
        $this->addSql('ALTER TABLE user DROP roles, DROP password, CHANGE name name TEXT NOT NULL COLLATE utf8_bin, CHANGE email email TEXT NOT NULL COLLATE utf8_bin, CHANGE native_language native_language SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT user_ibfk_1 FOREIGN KEY (native_language) REFERENCES language (id)');
        $this->addSql('CREATE INDEX native_language ON user (native_language)');
        $this->addSql('ALTER TABLE user_language CHANGE user_id user_id INT NOT NULL, CHANGE language_id language_id SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE vocabulary CHANGE language_a language_a SMALLINT NOT NULL, CHANGE language_b language_b SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE vocabulary_category CHANGE vocabulary_id vocabulary_id INT NOT NULL, CHANGE category_id category_id SMALLINT NOT NULL');
    }
}
