<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161104235342 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sylius_customer_group');
        $this->addSql('ALTER TABLE sylius_archetype DROP FOREIGN KEY FK_D19FF851727ACA70');
        $this->addSql('ALTER TABLE sylius_product DROP FOREIGN KEY FK_677B9B74732C6CC7');
        $this->addSql('ALTER TABLE sylius_product_archetype_attribute DROP FOREIGN KEY FK_97763342732C6CC7');
        $this->addSql('ALTER TABLE sylius_product_archetype_option DROP FOREIGN KEY FK_BCE763A7FE884EAC');
        $this->addSql('ALTER TABLE sylius_product_archetype_translation DROP FOREIGN KEY FK_E0BA36D2C2AC5D3');
        $this->addSql('CREATE TABLE sylius_locale (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(12) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_7BA1286477153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_customer_group (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7FCF9B0577153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE sylius_archetype');
        $this->addSql('DROP TABLE sylius_group');
        $this->addSql('DROP TABLE sylius_product_archetype_attribute');
        $this->addSql('DROP TABLE sylius_product_archetype_option');
        $this->addSql('DROP TABLE sylius_product_archetype_translation');
        $this->addSql('ALTER TABLE jdj_topic CHANGE author_id author_id INT NOT NULL');
        $this->addSql('DROP INDEX IDX_677B9B74732C6CC7 ON sylius_product');
        $this->addSql('ALTER TABLE sylius_product DROP archetype_id');
        $this->addSql('ALTER TABLE sylius_user ADD email_verification_token VARCHAR(255) DEFAULT NULL, ADD verified_at DATETIME DEFAULT NULL, ADD email VARCHAR(255) DEFAULT NULL, ADD email_canonical VARCHAR(255) DEFAULT NULL, CHANGE confirmation_token password_reset_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_customer ADD customer_group_id INT DEFAULT NULL, ADD subscribed_to_newsletter TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE sylius_customer ADD CONSTRAINT FK_7E82D5E6D2919A68 FOREIGN KEY (customer_group_id) REFERENCES sylius_customer_group (id)');
        $this->addSql('CREATE INDEX IDX_7E82D5E6D2919A68 ON sylius_customer (customer_group_id)');
        $this->addSql('ALTER TABLE sylius_user_oauth ADD refresh_token VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_customer DROP FOREIGN KEY FK_7E82D5E6D2919A68');
        $this->addSql('CREATE TABLE sylius_archetype (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_D19FF85177153098 (code), INDEX IDX_D19FF851727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_archetype_attribute (archetype_id INT NOT NULL, attribute_id INT NOT NULL, INDEX IDX_97763342732C6CC7 (archetype_id), INDEX IDX_97763342B6E62EFA (attribute_id), PRIMARY KEY(archetype_id, attribute_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_archetype_option (product_archetype_id INT NOT NULL, option_id INT NOT NULL, INDEX IDX_BCE763A7FE884EAC (product_archetype_id), INDEX IDX_BCE763A7A7C41D6F (option_id), PRIMARY KEY(product_archetype_id, option_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_archetype_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, locale VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX sylius_product_archetype_translation_uniq_trans (translatable_id, locale), INDEX IDX_E0BA36D2C2AC5D3 (translatable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_archetype ADD CONSTRAINT FK_D19FF851727ACA70 FOREIGN KEY (parent_id) REFERENCES sylius_archetype (id)');
        $this->addSql('ALTER TABLE sylius_product_archetype_attribute ADD CONSTRAINT FK_97763342732C6CC7 FOREIGN KEY (archetype_id) REFERENCES sylius_archetype (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_archetype_attribute ADD CONSTRAINT FK_97763342B6E62EFA FOREIGN KEY (attribute_id) REFERENCES sylius_product_attribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_archetype_option ADD CONSTRAINT FK_BCE763A7A7C41D6F FOREIGN KEY (option_id) REFERENCES sylius_product_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_archetype_option ADD CONSTRAINT FK_BCE763A7FE884EAC FOREIGN KEY (product_archetype_id) REFERENCES sylius_archetype (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_archetype_translation ADD CONSTRAINT FK_E0BA36D2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_archetype (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE sylius_locale');
        $this->addSql('DROP TABLE sylius_customer_group');
        $this->addSql('ALTER TABLE jdj_topic CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_7E82D5E6D2919A68 ON sylius_customer');
        $this->addSql('ALTER TABLE sylius_customer DROP customer_group_id, DROP subscribed_to_newsletter');
        $this->addSql('ALTER TABLE sylius_product ADD archetype_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product ADD CONSTRAINT FK_677B9B74732C6CC7 FOREIGN KEY (archetype_id) REFERENCES sylius_archetype (id)');
        $this->addSql('CREATE INDEX IDX_677B9B74732C6CC7 ON sylius_product (archetype_id)');
        $this->addSql('ALTER TABLE sylius_user ADD confirmation_token VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, DROP password_reset_token, DROP email_verification_token, DROP verified_at, DROP email, DROP email_canonical');
        $this->addSql('ALTER TABLE sylius_user_oauth DROP refresh_token');
    }
}
