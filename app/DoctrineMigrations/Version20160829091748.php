<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160829091748 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cpta_customer DROP FOREIGN KEY FK_A129E01EF5B7AF75');
        $this->addSql('ALTER TABLE cpta_dealer DROP FOREIGN KEY FK_960AF74EF5B7AF75');
        $this->addSql('ALTER TABLE cpta_bill_product DROP FOREIGN KEY FK_D9A28B271A8C12F5');
        $this->addSql('ALTER TABLE cpta_subscription DROP FOREIGN KEY FK_5DEDDF46ABABCA7F');
        $this->addSql('ALTER TABLE cpta_bill DROP FOREIGN KEY FK_151E8E7DA428E2FF');
        $this->addSql('ALTER TABLE cpta_bill DROP FOREIGN KEY FK_151E8E7D9395C3F3');
        $this->addSql('ALTER TABLE cpta_subscription DROP FOREIGN KEY FK_5DEDDF469395C3F3');
        $this->addSql('ALTER TABLE cpta_bill DROP FOREIGN KEY FK_151E8E7D249E6EA1');
        $this->addSql('ALTER TABLE cpta_bill DROP FOREIGN KEY FK_151E8E7DF57FBCCC');
        $this->addSql('ALTER TABLE cpta_book_entry DROP FOREIGN KEY FK_8A6A60D0F57FBCCC');
        $this->addSql('ALTER TABLE cpta_bill_product DROP FOREIGN KEY FK_D9A28B274584665A');
        $this->addSql('CREATE TABLE jdj_shopper (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_FFB1D12977153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_shopper_price (id INT AUTO_INCREMENT NOT NULL, shopper_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, price INT NOT NULL, createdAt DATETIME DEFAULT NULL, updatedAt DATETIME DEFAULT NULL, productVariant_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_CF90D140F47645AE (url), INDEX IDX_CF90D140FE2A96A4 (shopper_id), INDEX IDX_CF90D1405708BDEF (productVariant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_shopper_price ADD CONSTRAINT FK_CF90D140FE2A96A4 FOREIGN KEY (shopper_id) REFERENCES jdj_shopper (id)');
        $this->addSql('ALTER TABLE jdj_shopper_price ADD CONSTRAINT FK_CF90D1405708BDEF FOREIGN KEY (productVariant_id) REFERENCES sylius_product_variant (id)');
        $this->addSql('DROP TABLE cpta_address');
        $this->addSql('DROP TABLE cpta_bill');
        $this->addSql('DROP TABLE cpta_bill_product');
        $this->addSql('DROP TABLE cpta_book_entry');
        $this->addSql('DROP TABLE cpta_customer');
        $this->addSql('DROP TABLE cpta_dealer');
        $this->addSql('DROP TABLE cpta_payment_method');
        $this->addSql('DROP TABLE cpta_product');
        $this->addSql('DROP TABLE cpta_subscription');
        $this->addSql('DROP TABLE jdj_activity');
        $this->addSql('DROP TABLE jdj_notification');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_shopper_price DROP FOREIGN KEY FK_CF90D140FE2A96A4');
        $this->addSql('CREATE TABLE cpta_address (id INT AUTO_INCREMENT NOT NULL, street VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, additionalAddressInfo VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, postalCode VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, city VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cpta_bill (id INT AUTO_INCREMENT NOT NULL, dealer_id INT NOT NULL, customer_id INT NOT NULL, paidAt DATE DEFAULT NULL, createdAt DATETIME NOT NULL, customerAddressVersion INT NOT NULL, paymentMethod_id INT DEFAULT NULL, bookEntry_id INT DEFAULT NULL, dealerAddressVersion INT NOT NULL, UNIQUE INDEX UNIQ_151E8E7DA428E2FF (bookEntry_id), INDEX IDX_151E8E7D9395C3F3 (customer_id), INDEX IDX_151E8E7DF57FBCCC (paymentMethod_id), INDEX IDX_151E8E7D249E6EA1 (dealer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cpta_bill_product (id INT AUTO_INCREMENT NOT NULL, bill_id INT NOT NULL, product_id INT DEFAULT NULL, productVersion INT DEFAULT NULL, quantity INT NOT NULL, UNIQUE INDEX uniq_bill_product_idx (bill_id, product_id), INDEX IDX_D9A28B271A8C12F5 (bill_id), INDEX IDX_D9A28B274584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cpta_book_entry (id INT AUTO_INCREMENT NOT NULL, price NUMERIC(6, 2) NOT NULL, `label` VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, createdAt DATETIME NOT NULL, paymentMethod_id INT NOT NULL, activeAt DATETIME NOT NULL, state VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_8A6A60D0F57FBCCC (paymentMethod_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cpta_customer (id INT AUTO_INCREMENT NOT NULL, address_id INT NOT NULL, society VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, email VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_A129E01EF5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cpta_dealer (id INT AUTO_INCREMENT NOT NULL, address_id INT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_960AF74EF5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cpta_payment_method (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cpta_product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL COLLATE utf8_unicode_ci, price NUMERIC(6, 2) NOT NULL, subscriptionDuration INT NOT NULL, deletedAt DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cpta_subscription (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, status VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, createdAt DATETIME NOT NULL, startAt DATETIME DEFAULT NULL, endAt DATETIME DEFAULT NULL, billProduct_id INT NOT NULL, INDEX IDX_5DEDDF469395C3F3 (customer_id), INDEX IDX_5DEDDF46ABABCA7F (billProduct_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_activity (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jdj_notification (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cpta_bill ADD CONSTRAINT FK_151E8E7D249E6EA1 FOREIGN KEY (dealer_id) REFERENCES cpta_dealer (id)');
        $this->addSql('ALTER TABLE cpta_bill ADD CONSTRAINT FK_151E8E7D9395C3F3 FOREIGN KEY (customer_id) REFERENCES cpta_customer (id)');
        $this->addSql('ALTER TABLE cpta_bill ADD CONSTRAINT FK_151E8E7DA428E2FF FOREIGN KEY (bookEntry_id) REFERENCES cpta_book_entry (id)');
        $this->addSql('ALTER TABLE cpta_bill ADD CONSTRAINT FK_151E8E7DF57FBCCC FOREIGN KEY (paymentMethod_id) REFERENCES cpta_payment_method (id)');
        $this->addSql('ALTER TABLE cpta_bill_product ADD CONSTRAINT FK_D9A28B271A8C12F5 FOREIGN KEY (bill_id) REFERENCES cpta_bill (id)');
        $this->addSql('ALTER TABLE cpta_bill_product ADD CONSTRAINT FK_D9A28B274584665A FOREIGN KEY (product_id) REFERENCES cpta_product (id)');
        $this->addSql('ALTER TABLE cpta_book_entry ADD CONSTRAINT FK_8A6A60D0F57FBCCC FOREIGN KEY (paymentMethod_id) REFERENCES cpta_payment_method (id)');
        $this->addSql('ALTER TABLE cpta_customer ADD CONSTRAINT FK_A129E01EF5B7AF75 FOREIGN KEY (address_id) REFERENCES cpta_address (id)');
        $this->addSql('ALTER TABLE cpta_dealer ADD CONSTRAINT FK_960AF74EF5B7AF75 FOREIGN KEY (address_id) REFERENCES cpta_address (id)');
        $this->addSql('ALTER TABLE cpta_subscription ADD CONSTRAINT FK_5DEDDF469395C3F3 FOREIGN KEY (customer_id) REFERENCES cpta_customer (id)');
        $this->addSql('ALTER TABLE cpta_subscription ADD CONSTRAINT FK_5DEDDF46ABABCA7F FOREIGN KEY (billProduct_id) REFERENCES cpta_bill_product (id)');
        $this->addSql('DROP TABLE jdj_shopper');
        $this->addSql('DROP TABLE jdj_shopper_price');
    }
}
