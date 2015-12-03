<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151202135916 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cpta_subscription DROP FOREIGN KEY FK_5DEDDF464584665A');
        $this->addSql('DROP INDEX IDX_5DEDDF464584665A ON cpta_subscription');
        $this->addSql('ALTER TABLE cpta_subscription DROP product_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cpta_subscription ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE cpta_subscription ADD CONSTRAINT FK_5DEDDF464584665A FOREIGN KEY (product_id) REFERENCES cpta_product (id)');
        $this->addSql('CREATE INDEX IDX_5DEDDF464584665A ON cpta_subscription (product_id)');
    }
}
