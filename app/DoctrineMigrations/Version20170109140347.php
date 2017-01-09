<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170109140347 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_product_list (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, code VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, INDEX IDX_2BEEF75E7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_product_list_element (id INT AUTO_INCREMENT NOT NULL, list_id INT NOT NULL, product_id INT DEFAULT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, INDEX IDX_4E223E003DAE168B (list_id), INDEX IDX_4E223E004584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_product_list ADD CONSTRAINT FK_2BEEF75E7E3C61F9 FOREIGN KEY (owner_id) REFERENCES sylius_customer (id)');
        $this->addSql('ALTER TABLE jdj_product_list_element ADD CONSTRAINT FK_4E223E003DAE168B FOREIGN KEY (list_id) REFERENCES jdj_product_list (id)');
        $this->addSql('ALTER TABLE jdj_product_list_element ADD CONSTRAINT FK_4E223E004584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_product_list_element DROP FOREIGN KEY FK_4E223E003DAE168B');
        $this->addSql('DROP TABLE jdj_product_list');
        $this->addSql('DROP TABLE jdj_product_list_element');
    }
}
