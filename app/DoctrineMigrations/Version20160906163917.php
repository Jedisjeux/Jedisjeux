<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160906163917 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_shopper_price DROP FOREIGN KEY FK_CF90D140FE2A96A4');
        $this->addSql('CREATE TABLE jdj_prices_list (id INT AUTO_INCREMENT NOT NULL, dealer_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, headers TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_341DB6E7249E6EA1 (dealer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_prices_list ADD CONSTRAINT FK_341DB6E7249E6EA1 FOREIGN KEY (dealer_id) REFERENCES jdj_dealer (id)');
        $this->addSql('DROP TABLE jdj_shopper');
        $this->addSql('DROP TABLE jdj_shopper_price');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_shopper (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_FFB1D12977153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_shopper_price (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, shopper_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, price INT NOT NULL, status VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_CF90D140F47645AE (url), INDEX IDX_CF90D140FE2A96A4 (shopper_id), INDEX IDX_CF90D1404584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_shopper_price ADD CONSTRAINT FK_CF90D1404584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id)');
        $this->addSql('ALTER TABLE jdj_shopper_price ADD CONSTRAINT FK_CF90D140FE2A96A4 FOREIGN KEY (shopper_id) REFERENCES jdj_shopper (id)');
        $this->addSql('DROP TABLE jdj_prices_list');
    }
}
