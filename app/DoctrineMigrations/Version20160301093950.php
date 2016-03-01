<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160301093950 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_topic ADD mainTaxon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_topic ADD CONSTRAINT FK_F299315AA766DEB2 FOREIGN KEY (mainTaxon_id) REFERENCES Taxon (id)');
        $this->addSql('CREATE INDEX IDX_F299315AA766DEB2 ON jdj_topic (mainTaxon_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_topic DROP FOREIGN KEY FK_F299315AA766DEB2');
        $this->addSql('DROP INDEX IDX_F299315AA766DEB2 ON jdj_topic');
        $this->addSql('ALTER TABLE jdj_topic DROP mainTaxon_id');
    }
}
