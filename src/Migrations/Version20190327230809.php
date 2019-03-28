<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190327230809 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tense (id INT AUTO_INCREMENT NOT NULL, tensename_id INT NOT NULL, verb_id INT NOT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_BD0BEFEBF6A603A (tensename_id), INDEX IDX_BD0BEFEBC1D03483 (verb_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE verb (id INT AUTO_INCREMENT NOT NULL, infinitive VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tense_name (id INT AUTO_INCREMENT NOT NULL, language_id SMALLINT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_8F2096A91C9A06 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tense_guess (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tense_id INT NOT NULL, a2b_ok INT NOT NULL, a2b_ko INT NOT NULL, b2a_ok INT NOT NULL, b2a_ko INT NOT NULL, INDEX IDX_746BB207A76ED395 (user_id), INDEX IDX_746BB2078DC2CC67 (tense_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tense ADD CONSTRAINT FK_BD0BEFEBF6A603A FOREIGN KEY (tensename_id) REFERENCES tense_name (id)');
        $this->addSql('ALTER TABLE tense ADD CONSTRAINT FK_BD0BEFEBC1D03483 FOREIGN KEY (verb_id) REFERENCES verb (id)');
        $this->addSql('ALTER TABLE tense_name ADD CONSTRAINT FK_8F2096A91C9A06 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE tense_guess ADD CONSTRAINT FK_746BB207A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tense_guess ADD CONSTRAINT FK_746BB2078DC2CC67 FOREIGN KEY (tense_id) REFERENCES tense (id)');
        //$this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tense_guess DROP FOREIGN KEY FK_746BB2078DC2CC67');
        $this->addSql('ALTER TABLE tense DROP FOREIGN KEY FK_BD0BEFEBC1D03483');
        $this->addSql('ALTER TABLE tense DROP FOREIGN KEY FK_BD0BEFEBF6A603A');
        $this->addSql('DROP TABLE tense');
        $this->addSql('DROP TABLE verb');
        $this->addSql('DROP TABLE tense_name');
        $this->addSql('DROP TABLE tense_guess');
        $this->addSql('ALTER TABLE user CHANGE roles roles TEXT NOT NULL COLLATE utf8_bin');
    }
}
