<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160901125143 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE jdj_dealer_image (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jdj_dealer ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jdj_dealer ADD CONSTRAINT FK_161791EB3DA5256D FOREIGN KEY (image_id) REFERENCES jdj_dealer_image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_161791EB3DA5256D ON jdj_dealer (image_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE jdj_dealer DROP FOREIGN KEY FK_161791EB3DA5256D');
        $this->addSql('DROP TABLE jdj_dealer_image');
        $this->addSql('DROP INDEX UNIQ_161791EB3DA5256D ON jdj_dealer');
        $this->addSql('ALTER TABLE jdj_dealer DROP image_id');
    }
}
