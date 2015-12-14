<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151214130036 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cpta_dealer (id INT AUTO_INCREMENT NOT NULL, address_id INT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_960AF74EF5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cpta_dealer ADD CONSTRAINT FK_960AF74EF5B7AF75 FOREIGN KEY (address_id) REFERENCES cpta_address (id)');
        $this->addSql('ALTER TABLE cpta_bill ADD dealer_id INT DEFAULT NULL');
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
        $this->addSql('DROP TABLE cpta_dealer');
        $this->addSql('DROP INDEX IDX_151E8E7D249E6EA1 ON cpta_bill');
        $this->addSql('ALTER TABLE cpta_bill DROP dealer_id');
    }
}
