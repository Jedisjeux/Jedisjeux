<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160313181740 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sylius_user_oauth (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, provider VARCHAR(255) NOT NULL, identifier VARCHAR(255) NOT NULL, access_token VARCHAR(255) DEFAULT NULL, INDEX IDX_C3471B78A76ED395 (user_id), UNIQUE INDEX user_provider (user_id, provider), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_user (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, username VARCHAR(255) DEFAULT NULL, username_canonical VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, credentials_expire_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_569A33C09395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_customer (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, birthday DATETIME DEFAULT NULL, gender VARCHAR(1) DEFAULT \'u\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_7E82D5E6E7927C74 (email), UNIQUE INDEX UNIQ_7E82D5E6A0D96FBF (email_canonical), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_customer_group (customer_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_7FCF9B059395C3F3 (customer_id), INDEX IDX_7FCF9B05FE54D947 (group_id), PRIMARY KEY(customer_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_email (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, template VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) NOT NULL, subject VARCHAR(255) NOT NULL, sender_name VARCHAR(255) DEFAULT NULL, sender_address VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_732D4E1577153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_user_oauth ADD CONSTRAINT FK_C3471B78A76ED395 FOREIGN KEY (user_id) REFERENCES sylius_user (id)');
        $this->addSql('ALTER TABLE sylius_user ADD CONSTRAINT FK_569A33C09395C3F3 FOREIGN KEY (customer_id) REFERENCES sylius_customer (id)');
        $this->addSql('ALTER TABLE sylius_customer_group ADD CONSTRAINT FK_7FCF9B059395C3F3 FOREIGN KEY (customer_id) REFERENCES sylius_customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_customer_group ADD CONSTRAINT FK_7FCF9B05FE54D947 FOREIGN KEY (group_id) REFERENCES sylius_group (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_customer_group DROP FOREIGN KEY FK_7FCF9B05FE54D947');
        $this->addSql('ALTER TABLE sylius_user_oauth DROP FOREIGN KEY FK_C3471B78A76ED395');
        $this->addSql('ALTER TABLE sylius_user DROP FOREIGN KEY FK_569A33C09395C3F3');
        $this->addSql('ALTER TABLE sylius_customer_group DROP FOREIGN KEY FK_7FCF9B059395C3F3');
        $this->addSql('DROP TABLE sylius_user_oauth');
        $this->addSql('DROP TABLE sylius_group');
        $this->addSql('DROP TABLE sylius_user');
        $this->addSql('DROP TABLE sylius_customer');
        $this->addSql('DROP TABLE sylius_customer_group');
        $this->addSql('DROP TABLE sylius_email');
    }
}
