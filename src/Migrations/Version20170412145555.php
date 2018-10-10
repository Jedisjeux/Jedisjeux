<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170412145555 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sylius_product_association_type_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_4F618E52C2AC5D3 (translatable_id), UNIQUE INDEX sylius_product_association_type_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_variant_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_8DC18EDC2C2AC5D3 (translatable_id), UNIQUE INDEX sylius_product_variant_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_product_association_type_translation ADD CONSTRAINT FK_4F618E52C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_product_association_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_variant_translation ADD CONSTRAINT FK_8DC18EDC2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sylius_product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product DROP available_on, DROP available_until');
        $this->addSql('ALTER TABLE sylius_product_review DROP FOREIGN KEY FK_C7056A99F675F31B');
        $this->addSql('ALTER TABLE sylius_product_review CHANGE author_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE sylius_product_review ADD CONSTRAINT FK_C7056A99F675F31B FOREIGN KEY (author_id) REFERENCES sylius_customer (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX UNIQ_105A908989D9B62 ON sylius_product_translation');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_105A9084180C698989D9B62 ON sylius_product_translation (locale, slug)');
        $this->addSql('ALTER TABLE sylius_product_variant DROP name, DROP available_on, DROP available_until, DROP slug');
        $this->addSql('ALTER TABLE sylius_locale DROP enabled');
        $this->addSql('ALTER TABLE sylius_product_association_type DROP name');
        $this->addSql('ALTER TABLE sylius_product_attribute_value ADD locale_code VARCHAR(255) NOT NULL, ADD json_value LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\'');
        $this->addSql('ALTER TABLE sylius_product_option ADD position INT NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sylius_product_association_type_translation');
        $this->addSql('DROP TABLE sylius_product_variant_translation');
        $this->addSql('ALTER TABLE sylius_locale ADD enabled TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE sylius_product ADD available_on DATETIME DEFAULT NULL, ADD available_until DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product_association_type ADD name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE sylius_product_attribute_value DROP locale_code, DROP json_value');
        $this->addSql('ALTER TABLE sylius_product_option DROP position');
        $this->addSql('ALTER TABLE sylius_product_review DROP FOREIGN KEY FK_C7056A99F675F31B');
        $this->addSql('ALTER TABLE sylius_product_review CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product_review ADD CONSTRAINT FK_C7056A99F675F31B FOREIGN KEY (author_id) REFERENCES sylius_customer (id)');
        $this->addSql('DROP INDEX UNIQ_105A9084180C698989D9B62 ON sylius_product_translation');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_105A908989D9B62 ON sylius_product_translation (slug)');
        $this->addSql('ALTER TABLE sylius_product_variant ADD name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD available_on DATETIME DEFAULT NULL, ADD available_until DATETIME DEFAULT NULL, ADD slug VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
