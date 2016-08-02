<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160802101814 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_article ADD title VARCHAR(255) NOT NULL, ADD mainTaxon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_article ADD CONSTRAINT FK_DBEDE012A766DEB2 FOREIGN KEY (mainTaxon_id) REFERENCES Taxon (id)');
        $this->addSql('CREATE INDEX IDX_DBEDE012A766DEB2 ON jdj_article (mainTaxon_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_article DROP FOREIGN KEY FK_DBEDE012A766DEB2');
        $this->addSql('DROP INDEX IDX_DBEDE012A766DEB2 ON jdj_article');
        $this->addSql('ALTER TABLE jdj_article DROP title, DROP mainTaxon_id');
    }
}
