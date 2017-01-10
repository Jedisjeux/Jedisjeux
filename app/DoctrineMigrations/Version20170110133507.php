<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170110133507 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_product_list (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, code VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, INDEX IDX_2BEEF75E7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_product_list_product (productlist_id INT NOT NULL, productinterface_id INT NOT NULL, INDEX IDX_DC286494A6E1B0AC (productlist_id), INDEX IDX_DC2864945999C563 (productinterface_id), PRIMARY KEY(productlist_id, productinterface_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_product_list ADD CONSTRAINT FK_2BEEF75E7E3C61F9 FOREIGN KEY (owner_id) REFERENCES sylius_customer (id)');
        $this->addSql('ALTER TABLE jdj_product_list_product ADD CONSTRAINT FK_DC286494A6E1B0AC FOREIGN KEY (productlist_id) REFERENCES jdj_product_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jdj_product_list_product ADD CONSTRAINT FK_DC2864945999C563 FOREIGN KEY (productinterface_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_product_list_product DROP FOREIGN KEY FK_DC286494A6E1B0AC');
        $this->addSql('DROP TABLE jdj_product_list');
        $this->addSql('DROP TABLE jdj_product_list_product');
    }
}
