<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151203133206 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cpta_subscription DROP FOREIGN KEY FK_5DEDDF461A8C12F5');
        $this->addSql('DROP INDEX IDX_5DEDDF461A8C12F5 ON cpta_subscription');
        $this->addSql('ALTER TABLE cpta_subscription DROP bill_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cpta_subscription ADD bill_id INT NOT NULL');
        $this->addSql('ALTER TABLE cpta_subscription ADD CONSTRAINT FK_5DEDDF461A8C12F5 FOREIGN KEY (bill_id) REFERENCES cpta_bill (id)');
        $this->addSql('CREATE INDEX IDX_5DEDDF461A8C12F5 ON cpta_subscription (bill_id)');
    }
}
