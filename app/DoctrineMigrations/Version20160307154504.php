<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160307154504 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_product ADD mainImage_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product ADD CONSTRAINT FK_677B9B7444D00FAF FOREIGN KEY (mainImage_id) REFERENCES jdj_image (id)');
        $this->addSql('CREATE INDEX IDX_677B9B7444D00FAF ON sylius_product (mainImage_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_product DROP FOREIGN KEY FK_677B9B7444D00FAF');
        $this->addSql('DROP INDEX IDX_677B9B7444D00FAF ON sylius_product');
        $this->addSql('ALTER TABLE sylius_product DROP mainImage_id');
    }
}
