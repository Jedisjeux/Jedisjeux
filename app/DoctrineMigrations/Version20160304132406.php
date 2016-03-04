<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160304132406 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sylius_archetype (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_D19FF85177153098 (code), INDEX IDX_D19FF851727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_archetype_option (product_archetype_id INT NOT NULL, option_id INT NOT NULL, INDEX IDX_BCE763A7FE884EAC (product_archetype_id), INDEX IDX_BCE763A7A7C41D6F (option_id), PRIMARY KEY(product_archetype_id, option_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_archetype_attribute (archetype_id INT NOT NULL, attribute_id INT NOT NULL, INDEX IDX_97763342732C6CC7 (archetype_id), INDEX IDX_97763342B6E62EFA (attribute_id), PRIMARY KEY(archetype_id, attribute_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_archetype_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_E0BA36D2C2AC5D3 (translatable_id), UNIQUE INDEX sylius_product_archetype_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_attribute (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, storage_type VARCHAR(255) NOT NULL, configuration LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_BFAF484A77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_attribute_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_93850EBA2C2AC5D3 (translatable_id), UNIQUE INDEX sylius_product_attribute_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_attribute_value (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, attribute_id INT NOT NULL, text_value LONGTEXT DEFAULT NULL, boolean_value TINYINT(1) DEFAULT NULL, integer_value INT DEFAULT NULL, float_value DOUBLE PRECISION DEFAULT NULL, datetime_value DATETIME DEFAULT NULL, date_value DATE DEFAULT NULL, INDEX IDX_8A053E544584665A (product_id), INDEX IDX_8A053E54B6E62EFA (attribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_option (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_E4C0EBEF77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_option_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, presentation VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_CBA491AD2C2AC5D3 (translatable_id), UNIQUE INDEX sylius_product_option_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_option_value (id INT AUTO_INCREMENT NOT NULL, option_id INT NOT NULL, code VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_F7FF7D4B77153098 (code), INDEX IDX_F7FF7D4BA7C41D6F (option_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product (id INT AUTO_INCREMENT NOT NULL, archetype_id INT DEFAULT NULL, available_on DATETIME DEFAULT NULL, available_until DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, INDEX IDX_677B9B74732C6CC7 (archetype_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_options (product_id INT NOT NULL, option_id INT NOT NULL, INDEX IDX_2B5FF0094584665A (product_id), INDEX IDX_2B5FF009A7C41D6F (option_id), PRIMARY KEY(product_id, option_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, meta_keywords VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_105A908989D9B62 (slug), INDEX IDX_105A9082C2AC5D3 (translatable_id), UNIQUE INDEX sylius_product_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_variant (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, is_master TINYINT(1) NOT NULL, presentation VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, available_on DATETIME DEFAULT NULL, available_until DATETIME DEFAULT NULL, INDEX IDX_A29B5234584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_variant_option_value (variant_id INT NOT NULL, option_value_id INT NOT NULL, INDEX IDX_76CDAFA13B69A9AF (variant_id), INDEX IDX_76CDAFA1D957CA06 (option_value_id), PRIMARY KEY(variant_id, option_value_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_archetype ADD CONSTRAINT FK_D19FF851727ACA70 FOREIGN KEY (parent_id) REFERENCES sylius_archetype (id)');
        $this->addSql('ALTER TABLE sylius_product_archetype_option ADD CONSTRAINT FK_BCE763A7FE884EAC FOREIGN KEY (product_archetype_id) REFERENCES sylius_archetype (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_archetype_option ADD CONSTRAINT FK_BCE763A7A7C41D6F FOREIGN KEY (option_id) REFERENCES sylius_product_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_archetype_attribute ADD CONSTRAINT FK_97763342732C6CC7 FOREIGN KEY (archetype_id) REFERENCES sylius_archetype (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_archetype_attribute ADD CONSTRAINT FK_97763342B6E62EFA FOREIGN KEY (attribute_id) REFERENCES sylius_product_attribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_archetype_translation ADD CONSTRAINT FK_E0BA36D2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_archetype (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_attribute_translation ADD CONSTRAINT FK_93850EBA2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_product_attribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_attribute_value ADD CONSTRAINT FK_8A053E544584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_attribute_value ADD CONSTRAINT FK_8A053E54B6E62EFA FOREIGN KEY (attribute_id) REFERENCES sylius_product_attribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_option_translation ADD CONSTRAINT FK_CBA491AD2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_product_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_option_value ADD CONSTRAINT FK_F7FF7D4BA7C41D6F FOREIGN KEY (option_id) REFERENCES sylius_product_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product ADD CONSTRAINT FK_677B9B74732C6CC7 FOREIGN KEY (archetype_id) REFERENCES sylius_archetype (id)');
        $this->addSql('ALTER TABLE sylius_product_options ADD CONSTRAINT FK_2B5FF0094584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_options ADD CONSTRAINT FK_2B5FF009A7C41D6F FOREIGN KEY (option_id) REFERENCES sylius_product_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_translation ADD CONSTRAINT FK_105A9082C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_variant ADD CONSTRAINT FK_A29B5234584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_variant_option_value ADD CONSTRAINT FK_76CDAFA13B69A9AF FOREIGN KEY (variant_id) REFERENCES sylius_product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_variant_option_value ADD CONSTRAINT FK_76CDAFA1D957CA06 FOREIGN KEY (option_value_id) REFERENCES sylius_product_option_value (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_archetype DROP FOREIGN KEY FK_D19FF851727ACA70');
        $this->addSql('ALTER TABLE sylius_product_archetype_option DROP FOREIGN KEY FK_BCE763A7FE884EAC');
        $this->addSql('ALTER TABLE sylius_product_archetype_attribute DROP FOREIGN KEY FK_97763342732C6CC7');
        $this->addSql('ALTER TABLE sylius_product_archetype_translation DROP FOREIGN KEY FK_E0BA36D2C2AC5D3');
        $this->addSql('ALTER TABLE sylius_product DROP FOREIGN KEY FK_677B9B74732C6CC7');
        $this->addSql('ALTER TABLE sylius_product_archetype_attribute DROP FOREIGN KEY FK_97763342B6E62EFA');
        $this->addSql('ALTER TABLE sylius_product_attribute_translation DROP FOREIGN KEY FK_93850EBA2C2AC5D3');
        $this->addSql('ALTER TABLE sylius_product_attribute_value DROP FOREIGN KEY FK_8A053E54B6E62EFA');
        $this->addSql('ALTER TABLE sylius_product_archetype_option DROP FOREIGN KEY FK_BCE763A7A7C41D6F');
        $this->addSql('ALTER TABLE sylius_product_option_translation DROP FOREIGN KEY FK_CBA491AD2C2AC5D3');
        $this->addSql('ALTER TABLE sylius_product_option_value DROP FOREIGN KEY FK_F7FF7D4BA7C41D6F');
        $this->addSql('ALTER TABLE sylius_product_options DROP FOREIGN KEY FK_2B5FF009A7C41D6F');
        $this->addSql('ALTER TABLE sylius_product_variant_option_value DROP FOREIGN KEY FK_76CDAFA1D957CA06');
        $this->addSql('ALTER TABLE sylius_product_attribute_value DROP FOREIGN KEY FK_8A053E544584665A');
        $this->addSql('ALTER TABLE sylius_product_options DROP FOREIGN KEY FK_2B5FF0094584665A');
        $this->addSql('ALTER TABLE sylius_product_translation DROP FOREIGN KEY FK_105A9082C2AC5D3');
        $this->addSql('ALTER TABLE sylius_product_variant DROP FOREIGN KEY FK_A29B5234584665A');
        $this->addSql('ALTER TABLE sylius_product_variant_option_value DROP FOREIGN KEY FK_76CDAFA13B69A9AF');
        $this->addSql('DROP TABLE sylius_archetype');
        $this->addSql('DROP TABLE sylius_product_archetype_option');
        $this->addSql('DROP TABLE sylius_product_archetype_attribute');
        $this->addSql('DROP TABLE sylius_product_archetype_translation');
        $this->addSql('DROP TABLE sylius_product_attribute');
        $this->addSql('DROP TABLE sylius_product_attribute_translation');
        $this->addSql('DROP TABLE sylius_product_attribute_value');
        $this->addSql('DROP TABLE sylius_product_option');
        $this->addSql('DROP TABLE sylius_product_option_translation');
        $this->addSql('DROP TABLE sylius_product_option_value');
        $this->addSql('DROP TABLE sylius_product');
        $this->addSql('DROP TABLE sylius_product_options');
        $this->addSql('DROP TABLE sylius_product_translation');
        $this->addSql('DROP TABLE sylius_product_variant');
        $this->addSql('DROP TABLE sylius_product_variant_option_value');
    }
}
