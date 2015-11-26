<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151126131527 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DELETE FROM cpta_subscription');
        $this->addSql('ALTER TABLE cpta_subscription ADD CONSTRAINT FK_5DEDDF46ABABCA7F FOREIGN KEY (billProduct_id) REFERENCES cpta_bill_product (id)');
        $this->addSql('CREATE INDEX IDX_5DEDDF46ABABCA7F ON cpta_subscription (billProduct_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cpta_subscription DROP FOREIGN KEY FK_5DEDDF46ABABCA7F');
        $this->addSql('DROP INDEX IDX_5DEDDF46ABABCA7F ON cpta_subscription');
    }
}
