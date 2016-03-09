<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160308140705 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_product_variant DROP FOREIGN KEY FK_A29B52344D00FAF');
        $this->addSql('DROP INDEX IDX_A29B52344D00FAF ON sylius_product_variant');
        $this->addSql('ALTER TABLE sylius_product_variant DROP mainImage_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_product_variant ADD mainImage_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product_variant ADD CONSTRAINT FK_A29B52344D00FAF FOREIGN KEY (mainImage_id) REFERENCES jdj_image (id)');
        $this->addSql('CREATE INDEX IDX_A29B52344D00FAF ON sylius_product_variant (mainImage_id)');
    }
}
