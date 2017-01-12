<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160916005034 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_price_list (id INT AUTO_INCREMENT NOT NULL, dealer_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, headers TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_B22853E2249E6EA1 (dealer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_price_list ADD CONSTRAINT FK_B22853E2249E6EA1 FOREIGN KEY (dealer_id) REFERENCES jdj_dealer (id)');
        $this->addSql('DROP TABLE jdj_prices_list');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_prices_list (id INT AUTO_INCREMENT NOT NULL, dealer_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, headers TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_341DB6E7249E6EA1 (dealer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_prices_list ADD CONSTRAINT FK_341DB6E7249E6EA1 FOREIGN KEY (dealer_id) REFERENCES jdj_dealer (id)');
        $this->addSql('DROP TABLE jdj_price_list');
    }
}
