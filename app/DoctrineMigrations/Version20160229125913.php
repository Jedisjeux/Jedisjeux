<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160229125913 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Taxon (id INT AUTO_INCREMENT NOT NULL, taxonomy_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, tree_left INT NOT NULL, tree_right INT NOT NULL, tree_level INT NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_9AA60CAF77153098 (code), INDEX IDX_9AA60CAF9557E6F6 (taxonomy_id), INDEX IDX_9AA60CAF727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Taxonomy (id INT AUTO_INCREMENT NOT NULL, root_id INT DEFAULT NULL, INDEX IDX_464DA6B79066886 (root_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_taxonomy_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_9F3F90D92C2AC5D3 (translatable_id), UNIQUE INDEX sylius_taxonomy_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_taxon_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, permalink VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_1487DFCF2C2AC5D3 (translatable_id), UNIQUE INDEX permalink_uidx (locale, permalink), UNIQUE INDEX sylius_taxon_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Taxon ADD CONSTRAINT FK_9AA60CAF9557E6F6 FOREIGN KEY (taxonomy_id) REFERENCES Taxonomy (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE Taxon ADD CONSTRAINT FK_9AA60CAF727ACA70 FOREIGN KEY (parent_id) REFERENCES Taxon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Taxonomy ADD CONSTRAINT FK_464DA6B79066886 FOREIGN KEY (root_id) REFERENCES Taxon (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE sylius_taxonomy_translation ADD CONSTRAINT FK_9F3F90D92C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES Taxonomy (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_taxon_translation ADD CONSTRAINT FK_1487DFCF2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES Taxon (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Taxon DROP FOREIGN KEY FK_9AA60CAF727ACA70');
        $this->addSql('ALTER TABLE Taxonomy DROP FOREIGN KEY FK_464DA6B79066886');
        $this->addSql('ALTER TABLE sylius_taxon_translation DROP FOREIGN KEY FK_1487DFCF2C2AC5D3');
        $this->addSql('ALTER TABLE Taxon DROP FOREIGN KEY FK_9AA60CAF9557E6F6');
        $this->addSql('ALTER TABLE sylius_taxonomy_translation DROP FOREIGN KEY FK_9F3F90D92C2AC5D3');
        $this->addSql('DROP TABLE Taxon');
        $this->addSql('DROP TABLE Taxonomy');
        $this->addSql('DROP TABLE sylius_taxonomy_translation');
        $this->addSql('DROP TABLE sylius_taxon_translation');
    }
}
