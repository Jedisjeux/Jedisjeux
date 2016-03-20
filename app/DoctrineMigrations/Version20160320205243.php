<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160320205243 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('delete from sylius_taxon_translation');
        $this->addSql('update jdj_topic set mainTaxon_id = NULL');
        $this->addSql('delete from sylius_product_taxon');
        $this->addSql('delete from Taxon');
        $this->addSql('delete from sylius_taxonomy_translation');
        $this->addSql('delete from Taxonomy');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
