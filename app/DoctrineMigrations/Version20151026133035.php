<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151026133035 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cpta_bill_product ADD id INT AUTO_INCREMENT NOT NULL, CHANGE product_id product_id INT DEFAULT NULL, CHANGE productVersion productVersion INT DEFAULT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_bill_product_idx ON cpta_bill_product (bill_id, product_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cpta_bill_product MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE cpta_bill_product DROP PRIMARY KEY');
        $this->addSql('DROP INDEX uniq_bill_product_idx ON cpta_bill_product');
        $this->addSql('ALTER TABLE cpta_bill_product DROP id, CHANGE product_id product_id INT NOT NULL, CHANGE productVersion productVersion INT NOT NULL');
    }
}
