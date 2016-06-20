<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160620134743 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Taxon DROP FOREIGN KEY FK_9AA60CAF9557E6F6');
        $this->addSql('ALTER TABLE sylius_taxonomy_translation DROP FOREIGN KEY FK_9F3F90D92C2AC5D3');
        $this->addSql('DROP TABLE Taxonomy');
        $this->addSql('DROP TABLE sylius_taxonomy_translation');
        $this->addSql('ALTER TABLE sylius_product DROP deleted_at');
        $this->addSql('ALTER TABLE sylius_product_variant DROP deleted_at');
        $this->addSql('DROP INDEX IDX_9AA60CAF9557E6F6 ON Taxon');
        $this->addSql('ALTER TABLE Taxon DROP deleted_at, CHANGE taxonomy_id tree_root INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Taxon ADD CONSTRAINT FK_9AA60CAFA977936C FOREIGN KEY (tree_root) REFERENCES Taxon (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_9AA60CAFA977936C ON Taxon (tree_root)');
        $this->addSql('ALTER TABLE sylius_product_option DROP name');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Taxonomy (id INT AUTO_INCREMENT NOT NULL, root_id INT DEFAULT NULL, INDEX IDX_464DA6B79066886 (root_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_taxonomy_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, locale VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX sylius_taxonomy_translation_uniq_trans (translatable_id, locale), INDEX IDX_9F3F90D92C2AC5D3 (translatable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Taxonomy ADD CONSTRAINT FK_464DA6B79066886 FOREIGN KEY (root_id) REFERENCES Taxon (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE sylius_taxonomy_translation ADD CONSTRAINT FK_9F3F90D92C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES Taxonomy (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Taxon DROP FOREIGN KEY FK_9AA60CAFA977936C');
        $this->addSql('DROP INDEX IDX_9AA60CAFA977936C ON Taxon');
        $this->addSql('ALTER TABLE Taxon ADD deleted_at DATETIME DEFAULT NULL, CHANGE tree_root taxonomy_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Taxon ADD CONSTRAINT FK_9AA60CAF9557E6F6 FOREIGN KEY (taxonomy_id) REFERENCES Taxonomy (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_9AA60CAF9557E6F6 ON Taxon (taxonomy_id)');
        $this->addSql('ALTER TABLE sylius_product ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product_option ADD name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE sylius_product_variant ADD deleted_at DATETIME DEFAULT NULL');
    }
}
