<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160302135337 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_jeu_note (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, jeu_id INT NOT NULL, note_id INT NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, INDEX IDX_A56383F3F675F31B (author_id), INDEX IDX_A56383F38C9E392E (jeu_id), INDEX IDX_A56383F326ED0855 (note_id), INDEX search_idx (jeu_id, author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_note (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, valeur INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_user_review (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL, body LONGTEXT NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, jeuNote_id INT NOT NULL, UNIQUE INDEX UNIQ_6A466932A3374967 (jeuNote_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_jeu_note ADD CONSTRAINT FK_A56383F3F675F31B FOREIGN KEY (author_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE jdj_jeu_note ADD CONSTRAINT FK_A56383F38C9E392E FOREIGN KEY (jeu_id) REFERENCES jdj_jeu (id)');
        $this->addSql('ALTER TABLE jdj_jeu_note ADD CONSTRAINT FK_A56383F326ED0855 FOREIGN KEY (note_id) REFERENCES jdj_note (id)');
        $this->addSql('ALTER TABLE jdj_user_review ADD CONSTRAINT FK_6A466932A3374967 FOREIGN KEY (jeuNote_id) REFERENCES jdj_jeu_note (id)');
        $this->addSql('ALTER TABLE Taxon ADD description LONGTEXT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_user_review DROP FOREIGN KEY FK_6A466932A3374967');
        $this->addSql('ALTER TABLE jdj_jeu_note DROP FOREIGN KEY FK_A56383F326ED0855');
        $this->addSql('DROP TABLE jdj_jeu_note');
        $this->addSql('DROP TABLE jdj_note');
        $this->addSql('DROP TABLE jdj_user_review');
        $this->addSql('ALTER TABLE Taxon DROP description');
    }
}
