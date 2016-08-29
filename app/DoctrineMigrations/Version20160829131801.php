<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160829131801 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_shopper_price DROP FOREIGN KEY FK_CF90D1405708BDEF');
        $this->addSql('DROP INDEX IDX_CF90D1405708BDEF ON jdj_shopper_price');
        $this->addSql('ALTER TABLE jdj_shopper_price CHANGE productvariant_id product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_shopper_price ADD CONSTRAINT FK_CF90D1404584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id)');
        $this->addSql('CREATE INDEX IDX_CF90D1404584665A ON jdj_shopper_price (product_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_shopper_price DROP FOREIGN KEY FK_CF90D1404584665A');
        $this->addSql('DROP INDEX IDX_CF90D1404584665A ON jdj_shopper_price');
        $this->addSql('ALTER TABLE jdj_shopper_price CHANGE product_id productVariant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_shopper_price ADD CONSTRAINT FK_CF90D1405708BDEF FOREIGN KEY (productVariant_id) REFERENCES sylius_product_variant (id)');
        $this->addSql('CREATE INDEX IDX_CF90D1405708BDEF ON jdj_shopper_price (productVariant_id)');
    }
}
