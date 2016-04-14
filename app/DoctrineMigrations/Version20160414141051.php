<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160414141051 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_customer_list_element ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_customer_list_element ADD CONSTRAINT FK_685D85D74584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id)');
        $this->addSql('CREATE INDEX IDX_685D85D74584665A ON jdj_customer_list_element (product_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_customer_list_element DROP FOREIGN KEY FK_685D85D74584665A');
        $this->addSql('DROP INDEX IDX_685D85D74584665A ON jdj_customer_list_element');
        $this->addSql('ALTER TABLE jdj_customer_list_element DROP product_id');
    }
}
