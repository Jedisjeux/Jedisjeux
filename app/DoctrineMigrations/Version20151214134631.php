<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151214134631 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cpta_bill DROP FOREIGN KEY FK_151E8E7D249E6EA1');
        $this->addSql('DROP INDEX IDX_151E8E7D249E6EA1 ON cpta_bill');
        $this->addSql('ALTER TABLE cpta_bill CHANGE dealer_id dealer_id INT NOT NULL');
        $this->addSql('ALTER TABLE cpta_bill ADD CONSTRAINT FK_151E8E7D249E6EA1 FOREIGN KEY (dealer_id) REFERENCES cpta_dealer (id)');
        $this->addSql('CREATE INDEX IDX_151E8E7D249E6EA1 ON cpta_bill (dealer_id)');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cpta_bill DROP FOREIGN KEY FK_151E8E7D249E6EA1');
        $this->addSql('DROP INDEX IDX_151E8E7D249E6EA1 ON cpta_bill');
        $this->addSql('ALTER TABLE cpta_bill CHANGE dealer_id dealer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cpta_bill ADD CONSTRAINT FK_151E8E7D249E6EA1 FOREIGN KEY (dealer_id) REFERENCES cpta_dealer (id)');
        $this->addSql('CREATE INDEX IDX_151E8E7D249E6EA1 ON cpta_bill (dealer_id)');
    }
}
